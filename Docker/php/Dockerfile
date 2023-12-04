FROM php:8.2-fpm

#install applications
RUN apt-get -y update
RUN apt-get -y install git
RUN apt-get -y install nano
RUN apt-get -y install zip
RUN apt-get -y install libzip-dev
RUN apt-get -y install libpq-dev

#install php extensions
RUN docker-php-ext-install pdo pdo_pgsql

#install dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash

#fix terminal wrapping
COPY Docker/common/.bashrc /tmp/
RUN cat /tmp/.bashrc >> /root/.bashrc

#prepare project
COPY . /var/www/checkbox/
RUN mkdir -m 777 -p /var/www/checkbox/var