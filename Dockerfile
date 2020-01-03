FROM ulsmith/rpi-raspbian-apache-php
ADD ./ /var/www/html
RUN chmod -R 0755 /var/www/html
RUN rm /var/www/html/index.html
RUN chmod +x /var/www/html/Libs/ContainerSetup/webContainerSetup.sh
RUN /var/www/html/Libs/ContainerSetup/webContainerSetup.sh /mnt/LOE/log/RandomWord.access.log
EXPOSE 443
CMD ["/run.sh"]
