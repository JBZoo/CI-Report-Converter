# JBZoo / CI-Report-Converter

[![Travis](https://travis-ci.org/JBZoo/CI-Report-Converter.svg?branch=master)](https://travis-ci.org/JBZoo/CI-Report-Converter)    [![CI](https://github.com/JBZoo/CI-Report-Converter/actions/workflows/main.yml/badge.svg?branch=master)](https://github.com/JBZoo/CI-Report-Converter/actions/workflows/main.yml)    [![Docker Cloud Build Status](https://img.shields.io/docker/cloud/build/jbzoo/ci-report-converter.svg)](https://hub.docker.com/r/jbzoo/ci-report-converter)    [![Coverage Status](https://coveralls.io/repos/JBZoo/CI-Report-Converter/badge.svg)](https://coveralls.io/github/JBZoo/CI-Report-Converter)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/CI-Report-Converter/coverage.svg)](https://shepherd.dev/github/JBZoo/CI-Report-Converter)    [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jbzoo/ci-report-converter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jbzoo/ci-report-converter/?branch=master)    
[![PHP Version](https://img.shields.io/packagist/php-v/jbzoo/ci-report-converter)](https://github.com/JBZoo/CI-Report-Converter/blob/master/composer.json)    [![PHP Strict Types](https://img.shields.io/badge/strict__types-%3D1-brightgreen)](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict)    [![Stable Version](https://poser.pugx.org/jbzoo/ci-report-converter/version)](https://packagist.org/packages/jbzoo/ci-report-converter)    [![Total Downloads](https://poser.pugx.org/jbzoo/ci-report-converter/downloads)](https://packagist.org/packages/jbzoo/ci-report-converter/stats)    [![Docker Pulls](https://img.shields.io/docker/pulls/jbzoo/ci-report-converter.svg)](https://hub.docker.com/r/jbzoo/ci-report-converter)    [![GitHub Issues](https://img.shields.io/github/issues/jbzoo/ci-report-converter)](https://github.com/JBZoo/CI-Report-Converter/issues)    [![GitHub License](https://img.shields.io/github/license/jbzoo/ci-report-converter)](https://github.com/JBZoo/CI-Report-Converter/blob/master/LICENSE)


<!-- START doctoc -->
 * [Installing](#installing)
 * [GitHub Action](#github-action)
 * [Usage](#usage)
 * [Examples](#examples)
 * [Available Directions](#available-directions)
 * [Unit tests and check code style](#unit-tests-and-check-code-style)
 * [License](#license)
<!-- END doctoc -->


I believe you are familiar with the huge zoo of various utilities for testing, checking code standards, linters etc. 
It's really often the output of utilities is not supported in popular CI systems (TeamCity, GitHub, etc...).
I guess you are lucky if the utility saves the error report in the `junit.xml` format, because it works pretty fine with almost all modern dev software.

But... My experience tells me it's the exception rather than the rule.
For example, I really like the good old [phpmd](https://github.com/phpmd/phpmd) utility (perhaps you have another _right_ opinion about the benefits. At least, it's just an example).
It doesn't integrate well with TeamCity/PhpStorm/GitHub. Everytime I spend a lot of time looking for results in the logs. But I really want to see error reporting instantly and pretty printed.

Therefore, I developed a converter that changes the report format for deep integration with CI systems and JetBrain IDEs.

Well... It may seem to you it's a useless thing, and _your favorite super tool_ works fine in TeamCity/PhpStorm. Just take a look at [the examples below](#examples).


At the moment it works with
  * Input formats:
    * `junit` - [see example](tests/fixtures/origin/phpunit/junit-simple.xml). The most popular sort of error report. 
    * `checkstyle` - [see example](tests/fixtures/origin/phpcs/codestyle.xml). It works for [Phan](https://github.com/phan/phan), [PHPcs](https://github.com/squizlabs/PHP_CodeSniffer) and others.
    * `phpmd-json` - [see example](tests/fixtures/origin/phpmd/json.json). The most detailed report of [PHPMD](https://github.com/phpmd/phpmd).
    * `phpmnd` - [see example](tests/fixtures/origin/phpmnd/phpmnd.xml). I know only [PHP Magic Numbers Detector](https://github.com/povils/phpmnd).
    * `psalm-json` - [see example](tests/fixtures/origin/psalm/json.json). The most detailed report of [Psalm](https://github.com/vimeo/psalm).
  * Output formats:
    * `junit` - The most popular sort of reporting.
    * `tc-tests` - [Reporting for TeamCity/PhpStorm/JetBrains](https://www.jetbrains.com/help/teamcity/service-messages.html#Reporting+Tests).
    * `github-cli` - [GitHub Actions](https://docs.github.com/en/actions/reference/workflow-commands-for-github-actions#setting-a-warning-message).
    * `tc-inspections` [Reporting Inspections in TeamCity](https://www.jetbrains.com/help/teamcity/service-messages.html#Reporting+Inspections).


### Installing

```sh
composer require jbzoo/ci-report-converter
php ./vendor/bin/ci-report-converter --help

# OR use phar file
wget https://github.com/JBZoo/CI-Report-Converter/releases/latest/download/ci-report-converter.phar
php ./ci-report-converter.phar --help

# OR just pull the Docker Image
docker run --rm jbzoo/ci-report-converter --help 
```


### GitHub Action
Action allows you to convert errors to the [GitHub Annotations format](https://docs.github.com/en/actions/reference/workflow-commands-for-github-actions#setting-a-warning-message).
 * See [demo of error output](https://github.com/JBZoo/CI-Report-Converter/actions/workflows/gh-action.yml)
 * To learn more [see different examples](.github/workflows/gh-action.yml?query=is%3Asuccess)

```yaml
- uses: jbzoo/ci-report-converter@2.2.0 # or see the latest version on releases page 
  with:
    # Source format of error report. Available options: checkstyle, junit, phpmd-json, phpmnd, psalm-json
    # Default value: junit
    # Required: true
    format: junit
    
    # Relative path to the file with the error report
    # Required: true
    file: build/junit.xml
```


### Usage

```
$ php ./vendor/bin/ci-report-converter convert --help
Description:
  Convert one report format to another

Usage:
  convert [options]

Options:
  -S, --input-format=INPUT-FORMAT    Source format. Available options: checkstyle, junit, phpmd-json, phpmnd, psalm-json
  -T, --output-format=OUTPUT-FORMAT  Target format. Available options: github-cli, junit, tc-inspections, tc-tests
  -N, --suite-name=SUITE-NAME        Set name of root suite
  -I, --input-file[=INPUT-FILE]      Use CLI input (STDIN, pipeline) OR use the option to define filename of source report
  -O, --output-file[=OUTPUT-FILE]    Use CLI output (STDOUT, pipeline) OR use the option to define filename with result
  -R, --root-path[=ROOT-PATH]        If option is set all absolute file paths will be converted to relative
  -F, --tc-flow-id[=TC-FLOW-ID]      Custom flowId for TeamCity output
  -h, --help                         Display this help message
  -q, --quiet                        Do not output any message
  -V, --version                      Display this application version
      --ansi                         Force ANSI output
      --no-ansi                      Disable ANSI output
  -n, --no-interaction               Do not ask any interactive question
  -v|vv|vvv, --verbose               Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

```

### Examples

... Screenshots are coming soon ...


### Available Directions

<p align="center"><!-- Auto-created image via JBZoo\PHPUnit\CiReportConverterReadmeTest__testBuildGraphManually -->
  <img src="https://mermaid.ink/img/eyJjb2RlIjoiZ3JhcGggTFI7XG4gICAgY2hlY2tzdHlsZV9zcmMoXCJDaGVja1N0eWxlLnhtbFwiKTtcbiAgICBjaS1yZXBvcnQtY29udmVydGVyKChcIkNJLVJlcG9ydC1Db252ZXJ0ZXJcIikpO1xuICAgIGdpdGh1Yi1jbGlfdGFyZ2V0KFwiR2l0SHViIEFjdGlvbnMgLSBDTElcIik7XG4gICAganVuaXRfc3JjKFwiSlVuaXQueG1sXCIpO1xuICAgIGp1bml0X3RhcmdldChcIkpVbml0LnhtbFwiKTtcbiAgICBwaHBtZC1qc29uX3NyYyhcIlBIUG1kLmpzb25cIik7XG4gICAgcGhwbW5kX3NyYyhcIlBIUG1uZC54bWxcIik7XG4gICAgcHNhbG0tanNvbl9zcmMoXCJQc2FsbS5qc29uXCIpO1xuICAgIHRjLWluc3BlY3Rpb25zX3RhcmdldChcIlRlYW1DaXR5IC0gSW5zcGVjdGlvbnNcIik7XG4gICAgdGMtdGVzdHNfdGFyZ2V0KFwiVGVhbUNpdHkgLSBUZXN0c1wiKTtcblxuICAgIGNoZWNrc3R5bGVfc3JjID09PiBjaS1yZXBvcnQtY29udmVydGVyO1xuICAgIGNpLXJlcG9ydC1jb252ZXJ0ZXIgPT0+IGdpdGh1Yi1jbGlfdGFyZ2V0O1xuICAgIGNpLXJlcG9ydC1jb252ZXJ0ZXIgPT0+IGp1bml0X3RhcmdldDtcbiAgICBjaS1yZXBvcnQtY29udmVydGVyID09PiB0Yy1pbnNwZWN0aW9uc190YXJnZXQ7XG4gICAgY2ktcmVwb3J0LWNvbnZlcnRlciA9PT4gdGMtdGVzdHNfdGFyZ2V0O1xuICAgIGp1bml0X3NyYyA9PT4gY2ktcmVwb3J0LWNvbnZlcnRlcjtcbiAgICBwaHBtZC1qc29uX3NyYyA9PT4gY2ktcmVwb3J0LWNvbnZlcnRlcjtcbiAgICBwaHBtbmRfc3JjID09PiBjaS1yZXBvcnQtY29udmVydGVyO1xuICAgIHBzYWxtLWpzb25fc3JjID09PiBjaS1yZXBvcnQtY29udmVydGVyO1xuXG5saW5rU3R5bGUgZGVmYXVsdCBpbnRlcnBvbGF0ZSBiYXNpczsiLCJtZXJtYWlkIjp7InRoZW1lIjoiZm9yZXN0In19">
</p>

```sh
php ./vendor/bin/ci-report-converter convert:map
```

| Source/Target          | CheckStyle.xml | GitHub Actions - CLI | JUnit.xml | PHPmd.json | PHPmnd.xml | Psalm.json | TeamCity - Inspections | TeamCity - Tests |
|:-----------------------|:--------------:|:--------------------:|:---------:|:----------:|:----------:|:----------:|:----------------------:|:----------------:|
| CheckStyle.xml         |       -        |         Yes          |    Yes    |     -      |     -      |     -      |          Yes           |       Yes        |
| GitHub Actions - CLI   |       -        |          -           |     -     |     -      |     -      |     -      |           -            |        -         |
| JUnit.xml              |       -        |         Yes          |    Yes    |     -      |     -      |     -      |          Yes           |       Yes        |
| PHPmd.json             |       -        |         Yes          |    Yes    |     -      |     -      |     -      |          Yes           |       Yes        |
| PHPmnd.xml             |       -        |         Yes          |    Yes    |     -      |     -      |     -      |          Yes           |       Yes        |
| Psalm.json             |       -        |         Yes          |    Yes    |     -      |     -      |     -      |          Yes           |       Yes        |
| TeamCity - Inspections |       -        |          -           |     -     |     -      |     -      |     -      |           -            |        -         |
| TeamCity - Tests       |       -        |          -           |     -     |     -      |     -      |     -      |           -            |        -         |




### Unit tests and check code style

```sh
make build
make test-all
```


### License

MIT
