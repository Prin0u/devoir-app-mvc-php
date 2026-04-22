# Image de base : PHP + Apache déjà configurés
FROM php:8.2-apache

# Définit le dossier de travail (utile pour logs et exécution)
WORKDIR /var/www/html

# Installation des extensions PHP nécessaires pour la base de données
RUN apt-get update && apt-get install -y libpq-dev git unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
    
# Active le module rewrite d’Apache
RUN a2enmod rewrite

# Logs errors
RUN echo "log_errors = On" >> /usr/local/etc/php/php.ini \
&& echo "error_log = /proc/1/fd/2" >> /usr/local/etc/php/php.ini \
&& echo "display_errors = Off" >> /usr/local/etc/php/php.ini

# Copie des fichiers de dépendances en premier (pour optimiser le cache Docker)
COPY composer.json composer.lock ./

# Installation des dépendances via Composer
RUN composer install --no-dev --optimize-autoloader

# Copie tout le projet dans le serveur Apache
COPY . /var/www/html/

# Définit le dossier public comme racine du site
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Remplace la config Apache pour pointer vers /public au lieu de /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
/etc/apache2/sites-available/*.conf

# Autorise les .htaccess
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' \
/etc/apache2/apache2.conf

