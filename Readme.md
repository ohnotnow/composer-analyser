# Composer Analyser

Composer Analyser is a PHP script that scans a project's Composer dependencies and produces a markdown report detailing:
- Installed dependencies and their versions
- Package descriptions and licenses
- Available updates
- Security vulnerabilities
- A summary of the findings

## Installation

Ensure you have PHP and Composer installed on your system. Clone this repository and navigate to its directory:

```sh
git clone https://github.com/ohnotnow/composer-analyser.git 
cd composer-analyser
```

## Usage

Run the script using PHP:

```sh
php composer-analyser.php > composer-report.md
```

This will generate a `composer-report.md` file containing a structured analysis of the project's dependencies.

## GitHub Action Integration

A sample GitHub Action workflow file (`composer-analyser.yml`) is included in the repository. To integrate it into your CI/CD pipeline, copy it to `.github/workflows/` in your project:

```sh
mkdir -p .github/workflows
cp composer-analyser.yml .github/workflows/
```

This will allow Composer Analyser to run automatically in your GitHub Actions pipeline.

## Example Output (truncated)

# Composer Package Analysis Report

Generated on: 2025-03-13 15:38:32

| Package | License | Description | Installed Version | Latest Version | Update Available | Security Issue |
|---------|---------|-------------|-------------------|----------------|------------------|----------------|
| appstract/laravel-options | MIT | Global options loaded from the database | 5.7.0 | 6.0.0 | Y | N |
| arielmejiadev/larapex-charts | MIT | Package to provide easy api to build apex charts o... | 6.0.0 | 8.1.0 | Y | N |
| aws/aws-crt-php | Apache-2.0 | AWS Common Runtime for PHP | v1.2.5 | v1.2.7 | Y | N |
| aws/aws-sdk-php | Apache-2.0 | AWS SDK for PHP - Use Amazon Web Services in your ... | 3.311.2 | 3.342.4 | Y | N |
| weidner/goutte | MIT | Laravel Facade for Goutte, a simple PHP Web Scrape... | 2.3.0 | 2.3.0 | N | N |
| zbateson/mail-mime-parser | BSD-2-Clause | MIME email message parser | 2.4.1 | 3.0.3 | Y | N |
| zbateson/mb-wrapper | BSD-2-Clause | Wrapper for mbstring with fallback to iconv for en... | 1.2.1 | 2.0.1 | Y | N |
| zbateson/stream-decorators | BSD-2-Clause | PHP psr7 stream decorators for mime message part s... | 1.2.1 | 2.1.1 | Y | N |

### Summary

- Total packages: 195
- Packages needing updates: 161
- Packages with security issues: 7

## License

This project is licensed under the MIT License.


