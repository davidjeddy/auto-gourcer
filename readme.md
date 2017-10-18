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

 - Really depends on how you plan to use this library.
 - As a [container service](./docs/readme_container_service.md) (recommended)
 - As a [PHP / Composer package](./docs/readme_php.md) (NOT recommended)

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

# Usage

### Basic:

     - Edit `./basic.php` and populate with creditials. !!! DO NOT COMMIT THIS CHANGE TO ANY REPOSITORY! !!!

### Dot ENV

    - `cp .env.dist .env`
    - Populate `.env` with appropriate values

### All

     - `docker-compose up --build -d`
     - Wait for the build process to complete
     - `docker exec AutoGourcer php ./basic.php`
     - Wait until the render process/s complete
     - Collect Bacon in `./renders` directory
