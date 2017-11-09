FROM php:7.1-fpm

RUN curl http://nginx.org/keys/nginx_signing.key | apt-key add - \
 && printf "deb http://nginx.org/packages/mainline/debian/ jessie nginx\ndeb-src http://nginx.org/packages/mainline/debian/ jessie nginx\n" > /etc/apt/sources.list.d/nginx.list \
 && apt-get update \
 && apt-get install -y \
      nginx \
      supervisor \
 && rm -rf /var/lib/apt/lists/*

COPY ops/docker/app/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ops/docker/app/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

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

COPY . /app
WORKDIR /app

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n"]
