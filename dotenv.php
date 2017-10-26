<?php
declare(strict_types=1);

include_once './vendor/autoload.php';

use \dje\AutoGourcer\AutoGourcer;
use \dje\AutoGourcer\Git;
use \dje\AutoGourcer\Gource;

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
        // load dotenv values. If this is already done in your project, you will not need to do this again.
        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();

        // Init. Git class.
        // We need Git credentials in order to access remote repositories.
        $gitClass = new Git();
        $gitClass->setHost(getenv('host'))
            ->setUser(getenv('username'))
            ->setPass(getenv('password'))
            ->setOrg(getenv('organization'));

        // Init. the Gource class with default properties
        $gourceClass = new Gource();

        // Init. AutoGourcer with default settings.
        $ag = new AutoGourcer();
        $ag->setGit($gitClass)
            ->setRepoCount(10)
            ->setGource($gourceClass);

        // Finally run the process.
        $ag->run();
    }
}

Run::program();
