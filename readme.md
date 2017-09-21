# Auto Gourcer

# What does this do?

Using [Gource](http://gource.io) and [PHP](http://php.net ) this library reads your top active repositories and renders the history in a cool looking video.

[Gource Examples](https://github.com/acaudwell/Gource/wiki/Videos)

# Badges

[![Latest Stable Version](https://poser.pugx.org/davidjeddy/auto-gourcer/v/stable?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Build Status](https://travis-ci.org/davidjeddy/auto-gourcer.svg?branch=master&)](https://travis-ci.org/davidjeddy/auto-gourcer)
[![Total Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/downloads?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![License](https://poser.pugx.org/davidjeddy/auto-gourcer/license?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![PHP version](https://badge.fury.io/ph/davidjeddy%2Fauto-gourcer.svg)](https://badge.fury.io/ph/davidjeddy%2Fauto-gourcer)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1d1a7b75-6eb7-4a4c-8585-9ac9c87370b9/mini.png)](https://insight.sensiolabs.com/projects/1d1a7b75-6eb7-4a4c-8585-9ac9c87370b9)

[![composer.lock](https://poser.pugx.org/davidjeddy/auto-gourcer/composerlock?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![GitHub issues](https://img.shields.io/github/issues/davidjeddy/auto-gourcer.svg?style=flat-square)](https://github.com/davidjeddy/auto-gourcer/issues)
[![GitHub forks](https://img.shields.io/github/forks/davidjeddy/auto-gourcer.svg?style=flat-square)](https://github.com/davidjeddy/auto-gourcer/network)
[![GitHub stars](https://img.shields.io/github/stars/davidjeddy/auto-gourcer.svg?style=flat-square)](https://github.com/davidjeddy/auto-gourcer/stargazers)

[![Monthly Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/d/monthly?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)
[![Daily Downloads](https://poser.pugx.org/davidjeddy/auto-gourcer/d/daily?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)

[![Latest Unstable Version](https://poser.pugx.org/davidjeddy/auto-gourcer/v/unstable?format=flat-square)](https://packagist.org/packages/davidjeddy/auto-gourcer)

[![Twitter](https://img.shields.io/twitter/url/https/github.com/davidjeddy/auto-gourcer/.svg?style=social)](https://twitter.com/intent/tweet?text=Wow:&url=%5Bobject%20Object%5D)

# Requirements

 - Really depends on how you plan to use this library.
 - As a [container service](./docs/readme_container.md) (recommended)
 - As a [PHP / Composer package](./docs/readme_php.md) (NOT recommended)

# RoadMap

 - 0.1.0 Initial Alpha release
 - 0.1.75 Add initial test suite
 - 0.2.0 Code Review 1 release
 - 0.3.0 Add GitHub integration
 - 0.4.0 Add GitLab integration
 - 0.4.5 Use Monolog et al for logging, remove echo()s.
 ...
 - 1.0.0 Public Release

# SCM host Integrations

Currently only [BitBucket](https://bitbucket.com) is supported; others are planned.

# Testing

From the root of the project run the following command.

 - `docker exec -it AutoGourcer ./vendor/bin/phpunit`
