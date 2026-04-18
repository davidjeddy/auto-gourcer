# Auto Gourcer

# What does this do?

Using [Gource](http://gource.io) and [PHP](http://php.net ) this library reads your top active repositories and renders the history in a cool looking video.

[Gource Examples](https://github.com/acaudwell/Gource/wiki/Videos)

# Requirements

 - Access to application code SCM
    - [BitBucket](https://bitbucket.org), [GitHub](https://github.com), etc.
 - Shell / Terminal Access
 - [Docker](https://www.docker.com/) client for Linux, OSX, or Windows

# Optional

 - Ability to add / edit CRON jobs

# Integrations

Currently only BitBucket is supported.

# RoadMap

 - 0.1.0 Initial Alpha release
 - 0.1.75 Add initial test suite
 - 0.2.0 Code Review 1 release
 - 0.3.0 Add GitHub integration
 - 0.3.5 Use Monolog et al for logging, remove echo()s.
 - 0.4.0 Add GitLab integration
 ...
 - 1.0.0 Public Release

# Installation

    cd {projecr root parent directory}
    git clone git@github.com:davidjeddy/auto-gourcer.git
    cd ./auto-gourcer

# Build

As the project uses docker no system dependencies are required! Edit the applications environmental file and then run the Docker build command

    cp .env.dist .env

Edit .env values as required based on SCM host

    docker-compose up --build

# Usage

Now that the application is configured and built, execution is a single command!

    docker exec -it autogourcer_gource_1 php /auto_gourcer/src/run.php

Wait for the process to complete.

Check ./renders directory for your Gource'd repository visualization!

# Optional Automation

Add process to your systems CRON process no less than every 24 hours.