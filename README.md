# JBZoo / CI-Report-Converter

[![Build Status](https://travis-ci.org/JBZoo/CI-Report-Converter.svg?branch=master)](https://travis-ci.org/JBZoo/CI-Report-Converter)    [![Coverage Status](https://coveralls.io/repos/JBZoo/CI-Report-Converter/badge.svg)](https://coveralls.io/github/JBZoo/CI-Report-Converter)    [![Psalm Coverage](https://shepherd.dev/github/JBZoo/CI-Report-Converter/coverage.svg)](https://shepherd.dev/github/JBZoo/CI-Report-Converter)    [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jbzoo/ci-report-converter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jbzoo/ci-report-converter/?branch=master)    [![CodeFactor](https://www.codefactor.io/repository/github/jbzoo/ci-report-converter/badge)](https://www.codefactor.io/repository/github/jbzoo/ci-report-converter/issues)    [![PHP Strict Types](https://img.shields.io/badge/strict__types-%3D1-brightgreen)](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict)    
[![Stable Version](https://poser.pugx.org/jbzoo/ci-report-converter/version)](https://packagist.org/packages/jbzoo/ci-report-converter)    [![Latest Unstable Version](https://poser.pugx.org/jbzoo/ci-report-converter/v/unstable)](https://packagist.org/packages/jbzoo/ci-report-converter)    [![Dependents](https://poser.pugx.org/jbzoo/ci-report-converter/dependents)](https://packagist.org/packages/jbzoo/ci-report-converter/dependents?order_by=downloads)    [![GitHub Issues](https://img.shields.io/github/issues/jbzoo/ci-report-converter)](https://github.com/JBZoo/CI-Report-Converter/issues)    [![Total Downloads](https://poser.pugx.org/jbzoo/ci-report-converter/downloads)](https://packagist.org/packages/jbzoo/ci-report-converter/stats)    [![GitHub License](https://img.shields.io/github/license/jbzoo/ci-report-converter)](https://github.com/JBZoo/CI-Report-Converter/blob/master/LICENSE)



### Installing

```sh
composer require jbzoo/toolbox-ci

# OR use phar file.
# Replace <VERSION> to the latest version. See releases page or badge above
wget https://github.com/JBZoo/Toolbox-CI/releases/download/<VERSION>/toolbox-ci.phar
```


### Usage

```
$ php ./vendor/bin/ci-report-converter convert --help
Description:
  Convert one report format to another

Usage:
  convert [options]

Options:
  -S, --input-format=INPUT-FORMAT    Source format. Available options: checkstyle, github-cli, junit, phpmd-json, phpmnd, psalm-json, tc-inspections, tc-tests
  -T, --output-format=OUTPUT-FORMAT  Target format. Available options: checkstyle, github-cli, junit, phpmd-json, phpmnd, psalm-json, tc-inspections, tc-tests
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

### Available Directions

<p align="center"><!-- Auto-created image via JBZoo\PHPUnit\CiReportConverterReadmeTest__testBuildGraphManually -->
  <img src="https://mermaid.ink/img/eyJjb2RlIjoiZ3JhcGggTFI7XG4gICAgY2hlY2tzdHlsZV9zcmMoXCJDaGVja1N0eWxlLnhtbFwiKTtcbiAgICBjaS1yZXBvcnQtY29udmVydGVyKChcIlRvb2xib3gtQ0lcIikpO1xuICAgIGdpdGh1Yi1jbGlfdGFyZ2V0KFwiR2l0SHViIEFjdGlvbnMgLSBDTElcIik7XG4gICAganVuaXRfc3JjKFwiSlVuaXQueG1sXCIpO1xuICAgIGp1bml0X3RhcmdldChcIkpVbml0LnhtbFwiKTtcbiAgICBwaHBtZC1qc29uX3NyYyhcIlBIUG1kLmpzb25cIik7XG4gICAgcGhwbW5kX3NyYyhcIlBIUG1uZC54bWxcIik7XG4gICAgcHNhbG0tanNvbl9zcmMoXCJQc2FsbS5qc29uXCIpO1xuICAgIHRjLWluc3BlY3Rpb25zX3RhcmdldChcIlRlYW1DaXR5IC0gSW5zcGVjdGlvbnNcIik7XG4gICAgdGMtdGVzdHNfdGFyZ2V0KFwiVGVhbUNpdHkgLSBUZXN0c1wiKTtcblxuICAgIGNoZWNrc3R5bGVfc3JjID09PiBjaS1yZXBvcnQtY29udmVydGVyO1xuICAgIGNpLXJlcG9ydC1jb252ZXJ0ZXIgPT0+IGdpdGh1Yi1jbGlfdGFyZ2V0O1xuICAgIGNpLXJlcG9ydC1jb252ZXJ0ZXIgPT0+IGp1bml0X3RhcmdldDtcbiAgICBjaS1yZXBvcnQtY29udmVydGVyID09PiB0Yy1pbnNwZWN0aW9uc190YXJnZXQ7XG4gICAgY2ktcmVwb3J0LWNvbnZlcnRlciA9PT4gdGMtdGVzdHNfdGFyZ2V0O1xuICAgIGp1bml0X3NyYyA9PT4gY2ktcmVwb3J0LWNvbnZlcnRlcjtcbiAgICBwaHBtZC1qc29uX3NyYyA9PT4gY2ktcmVwb3J0LWNvbnZlcnRlcjtcbiAgICBwaHBtbmRfc3JjID09PiBjaS1yZXBvcnQtY29udmVydGVyO1xuICAgIHBzYWxtLWpzb25fc3JjID09PiBjaS1yZXBvcnQtY29udmVydGVyO1xuXG5saW5rU3R5bGUgZGVmYXVsdCBpbnRlcnBvbGF0ZSBiYXNpczsiLCJtZXJtYWlkIjp7InRoZW1lIjoiZm9yZXN0In19">
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
