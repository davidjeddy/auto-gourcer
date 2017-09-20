<?php
declare(strict_types=1);

// if vendors dir does not exist, OR day of mont is 14; get composer.phar
if (!file_exists('./vendor') || !file_exists('./composer.phar') || date('d') == 14) {
	\exec('wget https://getcomposer.org/composer.phar -O ./composer.phar');
}

// install dependencies if not already installed
\exec('php composer.phar install --ansi --profile --prefer-dist -o -vvv');

// include everything needed to run the application
include_once '/auto-gourcer/vendor/autoload.php';
include_once '/auto-gourcer/src/AutoGourcer.php';
include_once '/auto-gourcer/src/Git.php';
include_once '/auto-gourcer/src/Gource.php';

use \dje\AutoGourcer\AutoGourcer;
use \dje\AutoGourcer\Git;
use \dje\AutoGourcer\Gource;

/**
 * 
 */
class Run
{
	/**
	 * 
	 */
    public static function program()
    {
        // At the very least we need Git credentials in order to access BitBucket repositories.
        // Recommendation: DO NOT put your username/password in your code! Use a dotenv library similar to
        // https://github.com/vlucas/phpdotenv to handle sensitive datum.
        $gitClass = new Git();
        $gitClass->setUser()->setPass()->setOrg();

        // Here we override some of the Gource class properties 
        $gourceClass = new Gource();
        $gourceClass->setFramerate(30);

        // Override the AutoGourcer defaults
        $ag = new AutoGourcer();
        $ag->setRepoCount(1)->setGit($gitClass)->setGource($gourceClass);

        // Finally run the process
        $ag->run();
	}	
}

Run::program();
