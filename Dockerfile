FROM ubuntu:xenial
RUN apt update && apt install -y apache2 libapache2-mod-php7.0 && apt-get clean && rm -rf /var/cache/apt/archives /var/lib/apt/lists
EXPOSE 80 443
CMD ["/usr/sbin/apachectl","-D","FOREGROUND"]