<?php

include_once '/auto_gourcer/vendor/autoload.php';
include_once '/auto_gourcer/src/AutoGourcer.php';
include_once '/auto_gourcer/src/Git.php';
include_once '/auto_gourcer/src/Gource.php';

use \dje\AutoGourcer\Git;
use \dje\AutoGourcer\Gource;
use \dje\AutoGourcer\AutoGourcer;

/**
 * @param $a
 * @param $b
 * @return int
 */

// TODO how to call a class->method as a callback function?
function sortInReverseOrder($a,$b)
{
    return ($a['utc_last_updated'] <= $b['utc_last_updated']) ? 1 : -1;
}

// pass in AutoGource options on instantiation
$gitClass  = new Git();
$gitClass->setHost('<REMOTE_HOST>')->setUser('<GIT_USER>')->setPass('<GIT_PASS>')->setOrg('<BB_ORG>');

$gourceClass= new Gource();

$ag = new AutoGourcer();
$ag->setGit($gitClass)->setGource($gourceClass)->run();
