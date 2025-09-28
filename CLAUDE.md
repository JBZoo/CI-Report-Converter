# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CI-Report-Converter is a PHP library that converts error reports between different formats for better integration with CI systems and IDEs. It transforms reports from tools like PHPcs, PHPmd, Psalm, PHPStan into formats compatible with TeamCity, GitHub Actions, JetBrains IDEs, and other platforms.

## Development Commands

### Essential Commands
- `make build` - Install dependencies and build phar file
- `make test` - Run PHPUnit tests
- `make test-all` - Run tests and code style checks
- `make codestyle` - Run code style analysis (via jbzoo/codestyle)

### Testing Commands
- `php ./vendor/bin/phpunit` - Run PHPUnit tests directly
- `php ./vendor/bin/phpunit --configuration ./phpunit.xml.dist` - Run with specific config
- `make test-example` - Run example tests and generate fixtures

### Building
- `make build-phar` - Build standalone phar executable
- `make build-docker` - Build Docker image
- `make create-symlink` - Create symlink for testing

### The main binary
- `./ci-report-converter` - Main CLI executable (symlinked via make create-symlink)
- `./vendor/bin/ci-report-converter` - Alternative path to CLI

## Architecture

### Core Components

1. **Converters** (`src/Converters/`) - Transform input formats to internal representation
   - Input converters: CheckStyle, JUnit, PHPmd-JSON, PHPmnd, Psalm-JSON, PMD-CPD
   - Output converters: TeamCity Tests/Inspections, GitHub CLI, GitLab JSON, JUnit, Plain Text
   - Stats converters: Extract metrics for TeamCity statistics

2. **Formats** (`src/Formats/`) - Define data structures for different report formats
   - `Source/` - Internal canonical format (SourceSuite, SourceCase, SourceCaseOutput)
   - `JUnit/` - JUnit XML format classes
   - `TeamCity/` - TeamCity service messages format
   - `GithubActions/` - GitHub Actions annotations format
   - `MetricMaps/` - Metric extraction from various tools

3. **Commands** (`src/Commands/`) - CLI command implementations
   - `Convert` - Main conversion command
   - `TeamCityStats` - Extract and report code metrics
   - `AbstractCommand` - Base command class

### Conversion Flow

1. Input format → Internal Source format (SourceSuite/SourceCase)
2. Internal format → Target output format
3. Output written to file or stdout

### Key Classes

- `Map` - Registry of available input/output converters
- `Helper` - Utility functions for path manipulation and validation
- `AbstractConverter` - Base converter with common functionality

## Testing

- Tests located in `tests/` directory
- PHPUnit configuration: `phpunit.xml.dist`
- Coverage reports generated in `build/coverage_*`
- Fixture files in `tests/fixtures/` for various input/output formats

## Dependencies

- PHP 8.2+ required
- Symfony Console for CLI interface
- JBZoo packages (cli, data, utils, markdown)
- Development dependencies include jbzoo/toolbox-dev for code quality tools

## File Structure

- `src/` - Main source code
- `tests/` - Test files and fixtures
- `ci-report-converter` - Main executable (shell script wrapper)
- `Makefile` - Build and development commands
- `composer.json` - Dependency and autoload configuration