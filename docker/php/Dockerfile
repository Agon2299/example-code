FROM laradock/php-fpm:2.7-7.4

RUN set -xe && \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq && \
      docker-php-ext-install pgsql && \
      pecl install -o -f redis && \
      rm -rf /tmp/pear && \
      docker-php-ext-enable redis && \
      docker-php-ext-install exif && \
      docker-php-ext-install bcmath && \
      pecl install memcached-3.1.3 && \
      docker-php-ext-enable memcached && \
      docker-php-ext-install opcache;

RUN curl -L https://github.com/docker/compose/releases/download/1.21.2/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose && \
    chmod +x /usr/local/bin/docker-compose && \
    docker-compose --version

ADD ./docker/php/laravel.ini /usr/local/etc/php/conf.d
ADD ./docker/php/xlaravel.pool.conf /usr/local/etc/php-fpm.d/
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

USER root

RUN apt-get install -y jpegoptim optipng pngquant gifsicle && \
    apt-get install -y libmagickwand-dev imagemagick && \
    pecl install imagick && \
    docker-php-ext-enable imagick

WORKDIR /srv/www/app

COPY ./composer.json .
COPY ./database ./database
COPY ./nova ./nova
COPY ./storage/app ./storage/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y --no-install-recommends \
        apt-utils \
		nano \
		curl \
		zlib1g-dev \
		libjpeg-dev \
		libpng-dev \
		libfreetype6-dev \
		git \
		locales \
		libc-client-dev libkrb5-dev nginx \
		libzip-dev zip unzip && \
    printf 'ru_RU.UTF-8 UTF-8\n' >> /etc/locale.gen && \
    locale-gen ru_RU.UTF-8 && \
    update-locale

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN composer config --global github-oauth.github.com 6283acbe1250f60632d926aee8378764de0286bc
RUN composer install --no-interaction --ignore-platform-reqs -o --no-scripts

RUN rm /etc/nginx/sites-enabled -rf && rm /etc/nginx/sites-available -rf


COPY . .

RUN cp ./.env.dev ./.env && \
    composer run-script post-create-project-cmd && \
    composer dump-autoload && \
    chmod -R 777 storage

CMD /bin/true
