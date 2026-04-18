<?php
declare(strict_types=1);
// NOTE: no changes to this file are tracked unless GiT's --no-assume-unchanged is set.
// @source https://stackoverflow.com/questions/9794931/keep-file-in-a-git-repo-but-dont-track-changes
// if vendors dir does not exist, OR day of mont is 14; get composer.phar
if (!file_exists('./vendor') || !file_exists('./composer.phar') || date('d') == 14) {
    \exec('cd ../ && wget https://getcomposer.org/composer.phar -O ./composer.phar');
}

// install dependencies if not already installed
\exec('cd ../ && php composer.phar install --ansi --profile --prefer-dist -o -vvv');

// include everything needed to run the application
include_once '/auto-gourcer/vendor/autoload.php';
include_once '/auto-gourcer/src/AutoGourcer.php';
include_once '/auto-gourcer/src/Git.php';
include_once '/auto-gourcer/src/Gource.php';
include_once '/auto-gourcer/src/RemoteHost/BitBucket.php';

use \dje\AutoGourcer\AutoGourcer;
use \dje\AutoGourcer\Git;
use \dje\AutoGourcer\Gource;
use \dje\AutoGourcer\RemoteHost\BitBucket;

/**
 *
 */
class Run
{
    /**
     * @throws Exception
     */
    public static function program()
    {
        // Configure cred's for remote host
        $remoteHost = new BitBucket();
        $remoteHost->setHost('bitbucket.org')->setUser('{USER}')->setPass('{PASS}')->setOrg('{ORGANIZATION}');

        // Using remote host, GiT repos
        $gitClass = new Git();
        $gitClass->setRemoteHost($remoteHost)->setRepoCount(5)->getRepos(); // not sure if we want to keep this or auto-trigger 'getRepos()'.

        // Here we override some of the Gource class properties
        $gourceClass = new Gource();
        $gourceClass->setFramerate(30)->setStartDate('2017-10-11');

        // Override the AutoGourcer defaults
        $ag = new AutoGourcer();
        $ag->setGit($gitClass)->setGource($gourceClass);


        // Finally run the process
        $ag->run();
    }
}

Run::program();
