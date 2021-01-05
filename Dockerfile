FROM ubuntu:14.04

RUN apt-get update; apt-get install -y --force-yes apache2 php5 libapache2-mod-php5 mysql-server php5-mysql php-pear
RUN apt-get update; apt-get install -y imagemagick
RUN pecl install imagick

ADD ifuse-code /var/www/html/

# set up database
ADD setup_database.sql /root/setup_database.sql
RUN /etc/init.d/mysql start && \
    echo "CREATE DATABASE ifusedb;" | mysql && \
    echo "CREATE USER 'ifuser'@'localhost' IDENTIFIED BY 'ifusepw';" | mysql && \
    echo "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON ifuse.* TO 'ifuser'@'localhost';" | mysql

RUN /etc/init.d/mysql start && \
    cat /root/setup_database.sql | mysql


# Configure iFUSE


# serve iFUSE
EXPOSE 80
CMD apachectl -D FOREGROUND
