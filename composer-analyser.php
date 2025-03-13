<?php
/**
 * Composer Package Analyzer
 * 
 * This script analyzes installed Composer packages, their versions, licenses,
 * available updates, and security vulnerabilities, then outputs a markdown table.
 * 
 * Usage: php composer-analyzer.php > composer-report.md
 */

class ComposerAnalyzer {
    private array $packages = [];
    private array $securityIssues = [];
    
    /**
     * Run the analysis and generate the report
     */
    public function analyze(): string {
        $this->getInstalledPackages();
        $this->getSecurityIssues();
        $this->checkAvailableUpdates();
        
        return $this->generateMarkdownReport();
    }
    
    /**
     * Get all installed packages and their licenses
     */
    private function getInstalledPackages(): void {
        // Get package information using 'composer show'
        $showOutput = [];
        exec('composer show --format=json', $showOutput);
        $showJsonOutput = implode("\n", $showOutput);
        $showData = json_decode($showJsonOutput, true);
        
        // Get license information using 'composer license'
        $licenseOutput = [];
        exec('composer license -f json', $licenseOutput);
        $licenseJsonOutput = implode("\n", $licenseOutput);
        $licenseData = json_decode($licenseJsonOutput, true);
        
        // Create a map of package names to license information
        $licenseMap = [];
        if (isset($licenseData['dependencies']) && is_array($licenseData['dependencies'])) {
            foreach ($licenseData['dependencies'] as $packageName => $packageInfo) {
                $licenseMap[$packageName] = isset($packageInfo['license']) 
                    ? implode(', ', (array)$packageInfo['license']) 
                    : 'Unknown';
            }
        }
        
        // Process package information and merge with license data
        if (isset($showData['installed']) && is_array($showData['installed'])) {
            foreach ($showData['installed'] as $package) {
                $name = $package['name'] ?? '';
                if (!$name) continue;
                
                $license = $licenseMap[$name] ?? 'Unknown';
                
                $this->packages[$name] = [
                    'name' => $name,
                    'version' => $package['version'] ?? 'Unknown',
                    'description' => $package['description'] ?? '',
                    'license' => $license,
                    'latest' => '',
                    'has_update' => 'N',
                    'security_issue' => 'N'
                ];
            }
        }
    }
    
    /**
     * Check for security issues using 'composer audit'
     */
    private function getSecurityIssues(): void {
        $output = [];
        exec('composer audit --format=json', $output);
        $jsonOutput = implode("\n", $output);
        $data = json_decode($jsonOutput, true);
        
        if (isset($data['advisories']) && is_array($data['advisories'])) {
            foreach ($data['advisories'] as $packageName => $issues) {
                $this->securityIssues[$packageName] = true;
                
                if (isset($this->packages[$packageName])) {
                    $this->packages[$packageName]['security_issue'] = 'Y';
                }
            }
        }
    }
    
    /**
     * Check for available updates using 'composer outdated'
     */
    private function checkAvailableUpdates(): void {
        $output = [];
        exec('composer outdated --format=json', $output);
        $jsonOutput = implode("\n", $output);
        $data = json_decode($jsonOutput, true);
        
        if (isset($data['installed']) && is_array($data['installed'])) {
            foreach ($data['installed'] as $package) {
                $name = $package['name'] ?? '';
                if (!$name || !isset($this->packages[$name])) continue;
                
                $latest = $package['latest'] ?? '';
                if ($latest && $latest !== $this->packages[$name]['version']) {
                    $this->packages[$name]['latest'] = $latest;
                    $this->packages[$name]['has_update'] = 'Y';
                } else {
                    $this->packages[$name]['latest'] = $this->packages[$name]['version'];
                }
            }
        }
    }
    
    /**
     * Generate a markdown report from the collected data
     */
    private function generateMarkdownReport(): string {
        $markdown = "# Composer Package Analysis Report\n\n";
        $markdown .= "Generated on: " . date("Y-m-d H:i:s") . "\n\n";
        
        $markdown .= "| Package | License | Description | Installed Version | Latest Version | Update Available | Security Issue |\n";
        $markdown .= "|---------|---------|-------------|-------------------|----------------|------------------|----------------|\n";
        
        foreach ($this->packages as $package) {
            $markdown .= sprintf(
                "| %s | %s | %s | %s | %s | %s | %s |\n",
                $package['name'],
                $package['license'],
                substr($package['description'], 0, 50) . (strlen($package['description']) > 50 ? '...' : ''),
                $package['version'],
                $package['latest'] ?: $package['version'],
                $package['has_update'],
                $package['security_issue']
            );
        }
        
        $markdown .= "\n## Summary\n\n";
        
        // Count packages needing updates
        $updatesNeeded = array_filter($this->packages, function($package) {
            return $package['has_update'] === 'Y';
        });
        
        // Count packages with security issues
        $securityIssues = array_filter($this->packages, function($package) {
            return $package['security_issue'] === 'Y';
        });
        
        $markdown .= "- Total packages: " . count($this->packages) . "\n";
        $markdown .= "- Packages needing updates: " . count($updatesNeeded) . "\n";
        $markdown .= "- Packages with security issues: " . count($securityIssues) . "\n";
        
        return $markdown;
    }
}

// Run the analyzer
try {
    $analyzer = new ComposerAnalyzer();
    $report = $analyzer->analyze();
    echo $report;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

