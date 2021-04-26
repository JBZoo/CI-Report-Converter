#
# JBZoo Toolbox - CI-Report-Converter
#
# This file is part of the JBZoo Toolbox project.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package    CI-Report-Converter
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @link       https://github.com/JBZoo/CI-Report-Converter
#

.PHONY: build

ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif

build: ##@Project Install all 3rd party dependencies
	$(call title,"Install/Update all 3rd party dependencies")
	@composer install --optimize-autoloader --no-progress
	@make build-phar
	@make create-symlink


update: ##@Project Install/Update all 3rd party dependencies
	@echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
	@composer update --optimize-autoloader --no-progress $(JBZOO_COMPOSER_UPDATE_FLAGS)
	@make create-symlink
	@make build-phar


test-all: ##@Project Run all project tests at once
	@make create-symlink
	@make test
	@make codestyle


create-symlink: ##@Project Create Symlink (alias for testing)
	@ln -sfv `pwd`/ci-report-converter `pwd`/vendor/bin/ci-report-converter
	@ln -sfv `pwd`/ci-report-converter `pwd`/vendor/bin/toolbox-ci


test-example:
	@-$(PHP_BIN) ./vendor/phpunit/phpunit/phpunit \
        --configuration ./phpunit.xml.dist        \
        ./tests/ExampleTest.php                   \
        --order-by=default
	@cp ./build/coverage_junit/main.xml ./tests/fixtures/phpunit/junit.xml
	@-$(PHP_BIN) ./vendor/phpunit/phpunit/phpunit \
        --configuration ./phpunit.xml.dist        \
        ./tests/ExampleTest.php                   \
        --order-by=default                        \
        --teamcity > ./tests/fixtures/phpunit/teamcity-real.txt
