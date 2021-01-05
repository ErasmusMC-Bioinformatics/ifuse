FROM ubuntu:14.04

# install dependencies
RUN apt-get update; apt-get install -y --force-yes apache2 php5 libapache2-mod-php5 mysql-server php5-mysql php-pear
RUN apt-get update; apt-get install -y imagemagick
RUN pecl install imagick

# add ifuse code to webroot
ADD ifuse-code /var/www/html/
RUN chmod 777 -R /var/www/html/ && rm /var/www/html/index.html

# set up database
ADD setup_database.sql /root/setup_database.sql
RUN /etc/init.d/mysql start && \
    echo "CREATE DATABASE ifusedb;" | mysql && \
    echo "CREATE USER 'ifuser'@'localhost' IDENTIFIED BY 'ifusepw';" | mysql && \
    echo "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON ifusedb.* TO 'ifuser'@'localhost';" | mysql

RUN /etc/init.d/mysql start && \
    cat /root/setup_database.sql | mysql


# Configure iFUSE
ADD startup.sh root/

# Serve iFUSE
EXPOSE 80
CMD ["bash", "/root/startup.sh"]
