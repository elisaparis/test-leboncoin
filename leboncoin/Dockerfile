FROM ubuntu:latest

# Install our needed packages
RUN apt-get update && apt-get -y upgrade && DEBIAN_FRONTEND=noninteractive apt-get -y install \
    apache2 libapache2-mod-php7.2 php7.2 php7.2-common php7.2-xml php7.2-mysql curl composer

# Enable apache mods.
RUN a2enmod php7.2
RUN a2enmod rewrite

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Copy our project
ADD ./ /var/www/leboncoin 

# Install the project
RUN cd /var/www/leboncoin && composer install 

# Update the default apache site with the config we created.
ADD docker/config/leboncoin.local.conf /etc/apache2/sites-enabled/000-default.conf

# Expose apache
EXPOSE 80

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND