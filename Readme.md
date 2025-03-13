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

## License

This project is licensed under the MIT License.


