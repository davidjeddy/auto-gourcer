<?php
declare(strict_types=1);
namespace dje\AutoGourcer;

use Dotenv\Dotenv;

/**
 * Class Runner
 * @package dje\AutoGourcer
 */
class Runner
{
    /**
     * @throws \Exception
     */
    public function program()
    {
        // load dotenv values. If this is already done in your project, you will not need to do this again.
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();

        // Init. Git class.
        // We need Git credentials in order to access remote repositories.
        $gitClass = new Git();
        $gitClass->setHost(getenv('host'))
            ->setUser(getenv('username'))
            ->setPass(getenv('password'))
            ->setOrg(getenv('organization'));

        // Init. the Gource class with default properties
        // @source https://stackoverflow.com/questions/9735604/the-best-way-to-get-the-first-and-last-day-of-last-month
        $gourceClass = new Gource();
        $gourceClass->dryRun = true;

        // Init. AutoGourcer with default settings.
        $ag = new AutoGourcer();
        $ag->setGit($gitClass)->setGource($gourceClass);

        // Finally run the process.
        $ag->run();

        // Transcode for a smaller file size
        $handBrake = new HandBrake();
        $handBrake->transcodeAll('./renders');
    }
}
