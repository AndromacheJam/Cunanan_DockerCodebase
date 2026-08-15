FROM devilbox/php-fpm:8.2-work

USER root

COPY conf.d/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]
