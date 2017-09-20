# PHP library 

## General Requirements

 - Shell / Terminal access: execute commands
 - unzip for your OS: library installation

 - [BitBucket](https://bitbucket.org): project source code repositories
 - [Composer](https://getcomposer.org/): library installation
 - [ffmpeg](https://www.ffmpeg.org/): video rendering
 - [Git](https://git-scm.com/): library installation
 - [Gource](http://gource.io/): render source code as a visual image
 - [PHP](http://php.net/): language library is written in
 - [VLC](https://www.videolan.org/vlc/index.html): view resulting video
 - [xvfb](wikipedia): render images into video w/o a display device
 
## Installation

 - Execute the following commands in a shell:
  
    `cd {project root}`

    `php composer.phar require davidjeddy/auto_gourcer`

## Usage

Basic usage is rather streight forward; the classes are loaded using [Composer](https://getcomposer.org/)'s auto-load
functionality. You only need to call the AutoGourcer class with the proper dependencies injected, let the library do
the rest.

### Basic
```PHP
<?php
namespace your/class/namespace;

use \davidjeddy\AutoGourcer\AutoGourcer;
use \davidjeddy\AutoGourcer\Git;
use \davidjeddy\AutoGourcer\Gource;

/**
 * 
 */
SomeClass
{
    /**
     * [someMethod description]
     * @return {[type]} [description]
     */
    public function someMethod()
    {
        // At the very least we need Git creditials in order to access BitBucket repositories.
        // Recommenation: DO NOT put your username/password in your code! Use a dotenv library similar to 
        // https://github.com/vlucas/phpdotenv to handle sensitive datum.
        $gitClass = new Git();
        $gitClass->setUser(<YOUR GIT USERNAME>);
        $gitClass->setPass(<YOUR GIT PASSWORD>);

        // This is the simplist implimentation. Using library all defaults.
        $ag = new AutoGourcer($gitClass, new Gource());
        $ag->run();
    }
}

```

### Advanced
```PHP
<?php
namespace your/class/namespace;

use \davidjeddy\AutoGourcer\AutoGourcer;
use \davidjeddy\AutoGourcer\Git;
use \davidjeddy\AutoGourcer\Gource;

/**
 * 
 */
SomeClass
{
    /**
     * [someMethod description]
     * @return {[type]} [description]
     */
    public function someMethod()
    {
        // At the very least we need Git creditials in order to access BitBucket repositories.
        // Recommenation: DO NOT put your username/password in your code! Use a dotenv library similar to 
        // https://github.com/vlucas/phpdotenv to handle sensitive datum.
        $gitClass = new Git();
        $gitClass->setUser(<YOUR GIT USERNAME>);
        $gitClass->setPass(<YOUR GIT PASSWORD>);

        
        // Here we override some of the Gource class properties 
        $gourceClass = new Gource();
        $gourceClass->setFramerate('60');
        $gourceClass->setResolution('1920x1080');

        // And finally here we override some library defaults
        $configAry = [
            'repoCount' => 5
        ];

        // This is an advanced implimentation. Overriding library defaults.
        $ag = new AutoGourcer($gitClass, $gourceClass, $configAry);
        $ag->run();
    }
}
```
## Output

Once the process has run successfully the result are available in the ./renders directory (by default).
