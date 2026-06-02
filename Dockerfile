FROM php:8.2-apache
# Install PDO MySQL extension needed for database connection
RUN docker-php-ext-install pdo pdo_mysql
# Enable Apache mod_rewrite for nice URLs if needed
RUN a2enmod rewrite
# Copy application files
COPY . /var/www/html/
# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
