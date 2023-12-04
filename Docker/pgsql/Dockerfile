FROM postgres

#install applications
RUN apt-get -y update
RUN apt-get -y install nano

#fix terminal wrapping
COPY Docker/common/.bashrc /tmp/
RUN cat /tmp/.bashrc >> /root/.bashrc