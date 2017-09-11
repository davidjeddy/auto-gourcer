FROM ubuntu:16.04

RUN export TERM=xterm

RUN apt-get clean -y

RUN apt-get update -y

RUN apt-get install -y \
        software-properties-common \
        python-software-properties

RUN add-apt-repository ppa:no1wantdthisname/ppa -y

RUN apt-get install -y \
        autoconf \
        build-essential \
        curl \
        pkg-config \
        gcc \
        git \
        libsdl2-dev \
        libsdl2-image-dev \
        libpcre3-dev \
        libfreetype6-dev \
        libglew-dev \
        libglm-dev \
        libboost-filesystem-dev \
        libpng12-dev \
        libtinyxml-dev\
        libfreetype6 \
        xvfb \
        xfonts-base \
        xfonts-75dpi \
        xfonts-100dpi \
        xfonts-cyrillic \
        gource \
        ffmpeg \
        libavcodec-extra

RUN apt remove -y libavcodec-ffmpeg-extra56

# install and compile gource
WORKDIR /gource
RUN git clone https://github.com/acaudwell/Gource.git ./
RUN ./autogen.sh && ./configure --with-tinyxml && make && make install

# install php 7.1
RUN apt-get install python-software-properties software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -y
RUN apt-get install -y php7.1

#RUN php ./src/ExecuteTask.php
CMD ["tail", "-f", "/dev/null"]