FROM ubuntu:16.04

RUN export TERM=xterm

RUN apt-get clean -y

# ffmpeg installation
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
        libavcodec-extra \
        wget \
        unzip
RUN apt remove -y libavcodec-ffmpeg-extra56

# gource installation
WORKDIR /gource_source
RUN git clone https://github.com/acaudwell/Gource.git ./
RUN ./autogen.sh && ./configure --with-tinyxml && make && make install

# php 7.1 installation
RUN apt-get install python-software-properties software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -y
RUN apt-get install -y php7.1 php7.1-dom php7.1-cli php7.1-json php7.1-common php7.1-mbstring php7.1-xml php7.1-dom php7.1-curl

# Change back to root of FS
WORKDIR /auto-gourcer

# exec container
CMD ["tail", "-f", "/dev/null"]