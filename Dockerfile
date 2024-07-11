# Use the official PHP image
FROM php:7.4-apache

# Copy the custom PHP file into the Apache document root
COPY . /var/www/html/

# Set file permissions
RUN touch /var/www/html/data.txt && chmod 666 /var/www/html/data.txt && chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
