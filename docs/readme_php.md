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

 - Via [Composer](https://getcomposer.org/)
  
    `cd {project root}`
    `php composer.phar require davidjeddy/auto_gourcer`

## Usage

Basic usage is rather streight forward; the classes are loaded using composer's auto-load functionality. You only need
call the AutoGourcer class with the proper dependencies injected and let the library do the rest.

Basic:
```PHP
include_once '../vendor/auto_loader.php');

use \davidjeddy\AutoGourcer\AutoGourcer;
use \davidjeddy\AutoGourcer\Git;
use \davidjeddy\AutoGourcer\Gource;

// This is the simplist implimentation. Using all defaults.
$ag = new AutoGourcer(new Git(), new Gource());
$ag->run();
```

Advanced:
Basic:
```PHP
include_once '../vendor/auto_loader.php');

use \davidjeddy\AutoGourcer\AutoGourcer;
use \davidjeddy\AutoGourcer\Git;
use \davidjeddy\AutoGourcer\Gource;

// This is the simplist implimentation. Using all defaults.
$ag = new AutoGourcer(new Git(), new Gource(), ['count' =>]);
$ag->run();
```
## Output

Once the process has run successfully the result are available in the ./renders directory (by default).
