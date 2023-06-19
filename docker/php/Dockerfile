FROM php:8.2.6-fpm
MAINTAINER Diego Gullo <diego_gullo@bizmate.biz>

RUN apt-get --allow-releaseinfo-change update && apt-get install -y wget libcurl4-gnutls-dev \
	libfreetype6-dev libmcrypt-dev libicu-dev libpq-dev git\
	&& pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug \
	&& docker-php-ext-install curl mysqli pdo_mysql bcmath \
	&& apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN  cd /usr/local/bin \
	&& bash -c "$(wget -qO - 'https://get.symfony.com/cli/installer')" '' --install-dir=/usr/local/bin \
