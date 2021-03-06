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

BOX_PHAR = https://github.com/box-project/box/releases/download/3.16.0/box.phar

build: ##@Project Install all 3rd party dependencies
	$(call title,"Install/Update all 3rd party dependencies")
	@composer install --optimize-autoloader --no-progress
	@make build-phar
	@make create-symlink


build-docker:
	$(call title,"Building Docker Image")
	@docker build -t jbzoo-ci-report-converter .


update: ##@Project Install/Update all 3rd party dependencies
	@echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
	@composer update --with-all-dependencies --optimize-autoloader --no-progress $(JBZOO_COMPOSER_UPDATE_FLAGS)
	@$(PHP_BIN) `pwd`/vendor/bin/composer-diff
	@make create-symlink


test-all: ##@Project Run all project tests at once
	@make create-symlink
	@make test
	@make codestyle


update-titles:
	@doctoc --notitle --update-only --github README.md


create-symlink: ##@Project Create Symlink (alias for testing)
	@ln -sfv `pwd`/ci-report-converter `pwd`/vendor/bin/ci-report-converter


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
