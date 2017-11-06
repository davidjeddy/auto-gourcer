# Auto Gourcer

# What does this do?

Reads your active repositories and renders the history in a cool looking video.

[Gource Examples](https://github.com/acaudwell/Gource/wiki/Videos)

# Badges

## Repository Stats

[![Latest Stable Version](https://poser.pugx.org/davidjeddy/auto-gourcer/v/stable?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Total Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/downloads?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Latest Unstable Version](https://poser.pugx.org/davidjeddy/auto-gourcer/v/unstable?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![License](https://poser.pugx.org/davidjeddy/auto-gourcer/license?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Monthly Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/d/monthly?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Daily Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/d/daily?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![composer.lock](https://poser.pugx.org/davidjeddy/auto-gourcer/composerlock?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)

## Code Climate 

[![Maintainability](https://api.codeclimate.com/v1/badges/61f99e48e1e1f2ab7119/maintainability)](https://codeclimate.com/github/davidjeddy/auto-gourcer/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/61f99e48e1e1f2ab7119/test_coverage)](https://codeclimate.com/github/davidjeddy/auto-gourcer/test_coverage)

## Scrutinizer CI

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/davidjeddy/auto-gourcer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/davidjeddy/auto-gourcer/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/davidjeddy/auto-gourcer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/davidjeddy/auto-gourcer/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/davidjeddy/auto-gourcer/badges/build.png?b=master)](https://scrutinizer-ci.com/g/davidjeddy/auto-gourcer/build-status/master)

## Sensio Lab's Insight

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1d1a7b75-6eb7-4a4c-8585-9ac9c87370b9/big.png)](https://insight.sensiolabs.com/projects/1d1a7b75-6eb7-4a4c-8585-9ac9c87370b9)

# Requirements

## As a container service (recommended):
## General Requirements

 - Shell / Terminal access: execute commands
 - [Docker](https://www.docker.com): library execution in isolation
 - [Git](https://git-scm.com/): library installation

## As an included PHP library package (NOT recommended):

 * Due to the high number of system level services, non container operation will not be documented

# SCM host Integrations

Currently only [BitBucket](https://bitbucket.com) is supported; others are planned.

# RoadMap

 - 0.1.0 Initial Alpha release
 - 0.1.75 Add initial test suite
 - 0.2.0 Code Review 1 release
 - 0.3.0 Add GitHub integration
 - 0.4.0 Add GitLab integration
 - 0.4.5 Use Monolog et al for logging, remove echo()s.
 - 0.5.0 Ignore common dependency directories: node_modules, vendor, bower, etc
 ...
 - 1.0.0 Public Release

# Change Log

## 0.2.5
 - Drop support for native execution
 - ADDED .env configuration system

# Configure

If using a creditials system like [dotenv](https://github.com/vlucas/phpdotenv), configure creditials:

    copy .env.dist .env

Edit values as appropriate

# Usage

### Dot ENV

Populate `.env` with appropriate values

    docker-compose up --build -d

Wait for the build process to complete

Install dependencies via composer [composer](https://getcomposer.org)

    docker exec AutoGourcer php composer.phar install --ansi --profile --prefer-dist --no-dev -o -vvv

Run the rendering process

    docker exec AutoGourcer php ./dotenv.php

Wait until the render process/s complete

Collect Bacon in `./renders` directory

# Testing

To execute the unit test suite run the following commands:

    docker exec AutoGourcer php composer.phar install --ansi --profile --prefer-source -o -vvv
    docker exec -it AutoGourcer ./vendor/bin/phpunit
