FROM php:8.2-cli

RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer update --no-interaction --prefer-dist && composer dump-autoload --optimize

COPY . .

CMD ["composer", "test"]
