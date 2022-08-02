FROM php:5.6-apache

# mysql_*
RUN docker-php-ext-install mysql

# gd - png/jpeg
RUN apt-get update && \
  apt-get install -y libpng-dev libjpeg-dev &&\
  docker-php-ext-configure gd --with-jpeg-dir && \
  docker-php-ext-install gd
