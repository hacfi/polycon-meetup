FROM php:7.1-fpm

RUN curl http://nginx.org/keys/nginx_signing.key | apt-key add - \
 && printf "deb http://nginx.org/packages/mainline/debian/ jessie nginx\ndeb-src http://nginx.org/packages/mainline/debian/ jessie nginx\n" > /etc/apt/sources.list.d/nginx.list \
 && apt-get update \
 && apt-get install -y \
      nginx \
      supervisor \
 && rm -rf /var/lib/apt/lists/*

COPY ops/docker-compose/app/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ops/docker-compose/app/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && echo "date.timezone=UTC" | tee /usr/local/etc/php/conf.d/date.ini \
 && pecl install redis-3.1.4 \
 && docker-php-ext-enable redis \
 && apt-get update \
 && apt-get install -y \
      git \
      libicu-dev \
      libpq-dev \
 && docker-php-ext-install -j$(nproc) intl pdo pdo_pgsql pgsql \
 && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug-2.5.5 \
 && docker-php-ext-enable xdebug \
 && echo "xdebug.idekey=PHPSTORM" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
 && echo "xdebug.remote_autostart=on" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
 && echo "xdebug.remote_enable=on" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
 && echo "xdebug.remote_host=192.168.99.1" | tee -a /usr/local/etc/php/conf.d/xdebug.ini

ENV XDEBUG_CONFIG "idekey=PHPSTORM"
ENV PHP_IDE_CONFIG "serverName=demo"

WORKDIR /app

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n"]
