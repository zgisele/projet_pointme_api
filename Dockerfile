FROM php:8.2-fpm

# Variables d'environnement
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    wget \
    gnupg \
    debian-keyring \
    debian-archive-keyring \
    apt-transport-https \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Caddy (méthode simplifiée)
RUN apt-get update && apt-get install -y debian-keyring debian-archive-keyring apt-transport-https curl && \
    curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg && \
    curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | tee /etc/apt/sources.list.d/caddy-stable.list && \
    apt-get update && apt-get install -y caddy && \
    rm -rf /var/lib/apt/lists/*

# Configuration PHP personnalisée
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/99-custom.ini && \
    echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/99-custom.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/99-custom.ini && \
    echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/99-custom.ini

# Définir le répertoire de travail
WORKDIR /var/www

# Copier uniquement composer.json et composer.lock d'abord (pour le cache Docker)
COPY composer.json composer.lock ./

# Installer les dépendances Composer
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copier tous les fichiers de l'application
COPY . .

# IMPORTANT: Créer TOUS les répertoires de cache IMMÉDIATEMENT
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    storage/app/public \
    bootstrap/cache

    # Créer les répertoires nécessaires et définir les permissions
RUN mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/framework/{cache,sessions,views} \
    && mkdir -p /var/www/bootstrap/cache

# Donner les permissions appropriées (www-data est l'utilisateur par défaut de PHP-FPM)
RUN chown -R www-data:www-data /var/www/storage \
    && chown -R www-data:www-data /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Permissions complètes (777 pour éviter tout problème)
RUN chmod -R 777 storage bootstrap/cache

# Finaliser l'installation de Composer
RUN composer dump-autoload --optimize

# Copier les fichiers de configuration
COPY Caddyfile /etc/caddy/Caddyfile
COPY start.sh /usr/local/bin/start.sh

# Rendre le script exécutable
RUN chmod +x /usr/local/bin/start.sh

# Vérifier que les répertoires existent bien
RUN ls -la storage/framework/cache/ && \
    ls -la bootstrap/cache/

# Port par défaut pour Railway
EXPOSE 8080

# Démarrer l'application

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh
CMD ["/usr/local/bin/start.sh"]

