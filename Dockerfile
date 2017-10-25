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
        git \
        gource \
        ffmpeg \
        libavcodec-extra \
        wget \
        unzip
RUN apt remove -y libavcodec-ffmpeg-extra56

RUN add-apt-repository ppa:stebbins/handbrake-releases
RUN apt-get update -y
RUN apt-get install HandBrakeCLI

# gource installation
WORKDIR /gource_source
RUN git clone https://github.com/acaudwell/Gource.git ./
RUN ./autogen.sh && ./configure --with-tinyxml && make && make install

# php 7.1 installation
RUN apt-get install python-software-properties software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -y
RUN apt-get install -y php7.1 php7.1-dom php7.1-cli php7.1-json php7.1-common php7.1-mbstring php7.1-xml php7.1-dom php7.1-curl

# Clean up time
RUN apt autoremove -y
RUN apt-get clean -y

# Change back to root of FS
COPY ./ /auto-gourcer
WORKDIR /auto-gourcer

# get composer
RUN wget https://getcomposer.org/composer.phar -O composer.phar
RUN php composer.phar install --ansi --prefer-dist --profile -o -vvv

# exec container
#CMD ["php", "dotenv.php"]
CMD ["tail", "-f", "/dev/null"]