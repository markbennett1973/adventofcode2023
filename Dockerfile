FROM ubuntu:latest

RUN apt-get -y update
RUN apt-get -y upgrade
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install tzdata
RUN apt-get install -y php php-xdebug

RUN echo xdebug.mode=debug >> /etc/php/8.1/cli/conf.d/20-xdebug.ini
RUN echo xdebug.discover_client_host=0 >> /etc/php/8.1/cli/conf.d/20-xdebug.ini
RUN echo xdebug.client_host=host.docker.internal >> /etc/php/8.1/cli/conf.d/20-xdebug.ini
RUN echo xdebug.start_with_request=yes >> /etc/php/8.1/cli/conf.d/20-xdebug.ini

WORKDIR /aoc