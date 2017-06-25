FROM ubuntu:16.04

RUN export TERM=xterm

RUN apt-get update -y

RUN apt-get install -y \
    software-properties-common \
    python-software-properties

RUN add-apt-repository ppa:no1wantdthisname/ppa -y

RUN apt-get update -y

RUN apt-get install -y \
    autoconf \
    build-essential \
    pkg-config \
    gcc \
    git \
    wget \
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
    git \
    xvfb \
    xfonts-base \
    xfonts-75dpi \
    xfonts-100dpi \
    xfonts-cyrillic \
    gource \
    ffmpeg \
    libavcodec-extra \
    vim

RUN apt remove -y libavcodec-ffmpeg-extra56

RUN git clone https://github.com/acaudwell/Gource.git ./gource
WORKDIR /gource
RUN ./autogen.sh && ./configure --with-tinyxml && make && make install

WORKDIR /ag
COPY ./ /ag/

#set this to be xvfb
CMD bash