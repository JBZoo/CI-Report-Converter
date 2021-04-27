#
# JBZoo Toolbox - Mock-Server
#
# This file is part of the JBZoo Toolbox project.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package    Mock-Server
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @link       https://github.com/JBZoo/Mock-Server
#

FROM php:7.4-cli-alpine
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions      \
    && sync                                             \
    && install-php-extensions                           \
        opcache                                         \
        zip                                             \
        @composer

COPY . /app
RUN cd /app                                                           \
    && composer install --no-dev --optimize-autoloader --no-progress  \
    && composer clear-cache

ENTRYPOINT ["/app/ci-report-converter"]
