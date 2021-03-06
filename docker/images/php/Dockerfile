# Для начала указываем исходный образ, он будет использован как основа
FROM php:7.4-fpm

# RUN выполняет идущую за ней команду в контексте нашего образа.
# В данном случае мы установим некоторые зависимости и модули PHP.
# Для установки модулей используем команду docker-php-ext-install.
# На каждый RUN создается новый слой в образе, поэтому рекомендуется объединять команды.
RUN apt-get update -y && apt-get install -y \
        curl \
        wget \
        libzip-dev \
        libcurl3-dev \
        libfreetype6-dev \
        libmcrypt-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libxml2-dev \
        libxslt-dev \
        libmagickwand-dev \
        libgd3 \
        libgd-dev \
        libwebp-dev \
        libonig-dev \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean -y

RUN docker-php-ext-configure gd \
         --with-freetype \
         --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mbstring mysqli pdo_mysql zip soap json xsl xml intl curl \
    && pecl install xdebug imagick \
    && docker-php-ext-enable imagick xdebug
RUN  apt-get update -y && apt-get install -y \
       librabbitmq-dev \
       libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp
RUN    rm -rf /var/lib/apt/lists/* \
    && apt-get clean -y

# Куда же без composer'а.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Добавим свой php.ini, можем в нем определять свои значения конфига
ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

# Указываем рабочую директорию для PHP
WORKDIR /var/www

# Запускаем контейнер
# Из документации: The main purpose of a CMD is to provide defaults for an executing container. These defaults can include an executable,
# or they can omit the executable, in which case you must specify an ENTRYPOINT instruction as well.
CMD ["php-fpm"]
