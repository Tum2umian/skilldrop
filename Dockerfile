# Use the official PHP image as the base image
FROM php:8.2-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Copy the PHP files into the container
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

