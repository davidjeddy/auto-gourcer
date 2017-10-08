<?php
declare(strict_types=1);
// @source https://stackoverflow.com/questions/9794931/keep-file-in-a-git-repo-but-dont-track-changes
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
include_once '/auto-gourcer/src/RemoteSources/BitBucket.php';
include_once '/auto-gourcer/src/RemoteSources/GitHub.php';


use \dje\AutoGourcer\RemoteSources\BitBucket;
use \dje\AutoGourcer\RemoteSources\GitHub;
use \dje\AutoGourcer\Git;
use \dje\AutoGourcer\Gource;
use \dje\AutoGourcer\AutoGourcer;

/**
 * Class Run
 */
class Run
{
    /**
     * @throws Exception
     */
    public static function program()
    {
        // Create Git class first as it is used twice.
        $gitClass = new Git();



        // BitBucket
        // Create client for each remote host using creditials.
        $host = new BitBucket();
        // Set creditials for the remote host
        $host->setUser('')->setPass('')->setOrg('');

        // Pass the git remote host to the Git class
        $gitClass->setHostClass($host);



        //GitHub
        // Create client for each remote host using creditials.
        $host = new GitHub();
        // Set creditials for the remote host
        $host->setUser('')->setPass('');

        // Pass the git remote host to the Git class
        $gitClass->setHostClass($host);



        // Once all the GitClasses have completed task execute Gource class
        $gourceClass = new Gource();
        // Here we override some of the Gource class properties
        $gourceClass->setFramerate(30);

        // Init the AutoGourcer class
        $ag = new AutoGourcer();
        // Override the AutoGourcer defaults
        $ag->setRepoCount(5)->setGit($gitClass)->setGource($gourceClass);

        // Finally run the rendering process via the AutoGourcer class
        $ag->run();
	}
}

Run::program();
