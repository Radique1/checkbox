FROM nginx

#install applications
RUN apt-get -y update
RUN apt-get -y install nano

#fix terminal wrapping
COPY Docker/common/.bashrc /tmp/
RUN cat /tmp/.bashrc >> /root/.bashrc

#prepare nginx
RUN rm /etc/nginx/conf.d/default.conf
COPY Docker/nginx/etc/ /etc/nginx/conf.d/

#prepare project
COPY . /var/www/checkbox/