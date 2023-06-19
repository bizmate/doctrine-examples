FROM php:8.2.6-fpm
MAINTAINER Diego Gullo <diego_gullo@bizmate.biz>

RUN apt-get update && apt-get install -y ssh git zip wget \
    ##&& pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug \
    && docker-php-ext-install mysqli pdo_mysql bcmath \
    && apt-get clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mkdir -p ~/.ssh && ssh-keyscan -H gitlab.com >> ~/.ssh/known_hosts
RUN mkdir -p /home/composer && chmod 777 /home/composer
