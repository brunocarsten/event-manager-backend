############################################
# stage 1 ─────────── “vendor builder”     #
############################################
FROM composer:2 AS vendor-builder
WORKDIR /app

COPY composer.json composer.lock ./

# ↓ instala dependências SEM executar scripts
RUN composer install \
        --no-dev \
        --prefer-dist \
        --no-scripts \
        --no-interaction \
        --optimize-autoloader


############################################
# stage 2 ─────────── “runtime image”      #
############################################
FROM php:8.2-fpm AS runtime

# … pacotes do sistema e extensões PHP …
RUN apt-get update && apt-get install -y \
        zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
        libjpeg-dev libfreetype6-dev libicu-dev git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql zip mbstring bcmath intl gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# opcional: binário do Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# vendor pronta
COPY --from=vendor-builder /app/vendor /var/www/vendor

# agora o projeto inteiro (garanta vendor/ em .dockerignore)
COPY . /var/www

# executa de novo o composer — praticamente instantâneo —
# só para rodar os scripts que ficaram pendentes
RUN composer install \
        --no-dev \
        --prefer-dist \
        --no-interaction \
        --optimize-autoloader

# agora tudo o que depende de artisan funciona
RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www \
    && chmod -R 777 /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
