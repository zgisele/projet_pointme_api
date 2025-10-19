# Choisir l'image PHP avec FPM
FROM php:8.2-fpm

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git curl \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copier les fichiers du projet
COPY . .

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader

# Installer les dépendances Node
RUN npm install && npm run build

# Exposer le port 8000 pour Laravel
EXPOSE 8000

# Lancer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
