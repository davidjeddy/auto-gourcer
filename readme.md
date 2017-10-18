# Auto Gourcer

# What does this do?

Using [Gource](http://gource.io) and [PHP](http://php.net ) this library reads your top active repositories and renders the history in a cool looking video.

[Gource Examples](https://github.com/acaudwell/Gource/wiki/Videos)

# Badges

[![Latest Stable Version](https://poser.pugx.org/davidjeddy/auto-gourcer/v/stable?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Total Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/downloads?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Latest Unstable Version](https://poser.pugx.org/davidjeddy/auto-gourcer/v/unstable?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![License](https://poser.pugx.org/davidjeddy/auto-gourcer/license?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Monthly Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/d/monthly?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Daily Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/d/daily?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![composer.lock](https://poser.pugx.org/davidjeddy/auto-gourcer/composerlock?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1d1a7b75-6eb7-4a4c-8585-9ac9c87370b9/big.png)](https://insight.sensiolabs.com/projects/1d1a7b75-6eb7-4a4c-8585-9ac9c87370b9)

# Requirements

## As a container service (recommended):
## General Requirements

 - Shell / Terminal access: execute commands
 - [Docker](https://www.docker.com): library execution in isolation
 - [Git](https://git-scm.com/): library installation

## As an included PHP library package (NOT recommended):

 - [BitBucket](https://bitbucket.org): project source code repositories
 - [Composer](https://getcomposer.org/): library installation
 - [ffmpeg](https://www.ffmpeg.org/): video rendering
 - [Git](https://git-scm.com/): library installation
 - [Gource](http://gource.io/): render source code as a visual image
 - [PHP](http://php.net/): language library is written in
 - [VLC](https://www.videolan.org/vlc/index.html): view resulting video
 - [xvfb](wikipedia): render images into video w/o a display device

 *** Remove any container service / docker related prepended commands herein if used as an included library. ***

# SCM host Integrations

Currently only [BitBucket](https://bitbucket.com) is supported; others are planned.

# RoadMap

 - 0.1.0 Initial Alpha release
 - 0.1.75 Add initial test suite
 - 0.2.0 Code Review 1 release
 - 0.3.0 Add GitHub integration
 - 0.4.0 Add GitLab integration
 - 0.4.5 Use Monolog et al for logging, remove echo()s.
 ...
 - 1.0.0 Public Release

# Install

The best way to install the library is via PHP's dependency manager [composer](https://getcomposer.org):

    `composer require davidjeddy/auto-gourcer`

# Configure

If using a creditials system like [dotenv](https://github.com/vlucas/phpdotenv), configure creditials:

    `copy .env.dist .env`, then edit values as appropriate

# Testing

To execute the unit test suite run the following command:

On host: `./vendor/bin/phpunit`
In container: `docker exec -it AutoGourcer ./vendor/bin/phpunit`

# Usage

### Dot ENV

    - `cp .env.dist .env`
    - Populate `.env` with appropriate values

### Basic (NOT RECOMMENDED):

     - Edit `./basic.php` and populate with creditials. !!! DO NOT COMMIT THIS CHANGE TO ANY REPOSITORY! !!!

### All

     - `docker-compose up --build -d`
     - Wait for the build process to complete
     - `docker exec AutoGourcer php ./basic.php`
     - Wait until the render process/s complete
     - Collect Bacon in `./renders` directory
