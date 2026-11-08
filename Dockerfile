FROM php:8.2-apache

# Install ekstensi pdo & pdo_mysql untuk koneksi database
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan modul mod_rewrite Apache untuk URL rewrite MVC
RUN a2enmod rewrite

# Arahkan Apache DocumentRoot langsung ke folder public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Berikan izin Override agar .htaccess pada folder public berfungsi
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/mvc-override.conf \
    && a2enconf mvc-override

WORKDIR /var/www/html
