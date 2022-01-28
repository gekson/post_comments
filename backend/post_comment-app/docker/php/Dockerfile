FROM devilbox/php-fpm-8.1:latest
# FROM php:8-fpm

# removing the ENV interactive to avoid dialogs to open in terminal of docker context.
ENV DEBIAN_FRONTEND noninteractive

# opcache configurations.
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="1" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="40531" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="4096" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10" \
    PHP_OPCACHE_FILE_OVERRIDE=1


# set up the uk timezone as well.
ENV TZ=Europe/London
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone


RUN apt-get update -y && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        graphviz \
        zip

# Install Dependencies
RUN ACCEPT_EULA=Y apt-get install -y \
    unixodbc \
    unixodbc-dev \
    libgss3 \
    odbcinst \
    locales \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && locale-gen

# SET UP C++ ENGINE ENHACEMENT.
RUN ln -s /usr/lib/x86_64-linux-gnu/libsybdb.a /usr/lib/

#install some base extensions
RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-source delete

# POSTGRESQL extensions.
RUN apt-get update -y && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# REDIS EXTENSION
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis


# copying custom php.ini configurations into the container.
# OPCACHE FOR PHP
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# creating the working DIR
RUN mkdir -p /code
ENV HOME=/code
WORKDIR $HOME

# setting the default user into the working dir
USER root
COPY ./ $HOME
