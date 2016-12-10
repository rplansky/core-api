FROM php:7.0-apache

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -qq && \
    apt-get install -qq --no-install-recommends \
        nano \
        htop \
        git \
        zip \
        libmcrypt-dev \
        libmagickwand-dev \
        libxslt1-dev && \
    rm -r /var/lib/apt/lists/*

RUN echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu trusty main" > /etc/apt/sources.list.d/ondrej-php.list && \
    echo "deb-src http://ppa.launchpad.net/ondrej/php/ubuntu trusty main" >> /etc/apt/sources.list.d/ondrej-php.list && \
    apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 4F4EA0AAE5267A6C && \
    apt-get update -qq && \
    apt-get install -qq --no-install-recommends \
        php7.0-dev && \
    rm -r /var/lib/apt/lists/*

RUN docker-php-ext-install pcntl xsl mcrypt zip ftp

RUN pecl install \
        mongodb \
        imagick \
        xdebug && \
    docker-php-ext-enable \
        mongodb \
        imagick \
        xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH=$PATH:/root/.composer/vendor/bin

RUN a2enmod rewrite && \
    ln -s /etc/apache2/mods-available/socache_shmcb.load /etc/apache2/mods-enabled/ && \
    sed -i "s/DocumentRoot .*/DocumentRoot \/var\/www\/html\/public/" /etc/apache2/sites-available/000-default.conf

RUN echo "xdebug.remote_enable=1"  >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_connect_back=1"  >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD custom.ini /usr/local/etc/php/conf.d/custom.ini

RUN usermod -u 1000 www-data