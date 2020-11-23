ARG APP_IMAGE
FROM ${APP_IMAGE}

ARG UID

RUN apt-get update && \
    apt-get install -y librabbitmq-dev

RUN pecl channel-update pecl.php.net

RUN docker-php-ext-install -j$(nproc) iconv

RUN pecl install redis-4.0.1 amqp-1.9.4 \
    && docker-php-ext-install sockets \
    && docker-php-ext-enable redis amqp

RUN useradd -m -s /bin/bash -u ${UID} dev && \
    apt -y update --fix-missing && \
    apt -y install vim mc wget git curl zip curl figlet unzip libmcrypt4 libmcrypt-dev && \
    docker-php-ext-install pdo_mysql mysqli && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    echo "su - dev" > /root/.bashrc

WORKDIR "/application"