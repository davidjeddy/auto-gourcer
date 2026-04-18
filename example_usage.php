<?php
include_once '/auto_gourcer/vendor/autoload.php';
include_once '/auto_gourcer/src/AutoGourcer.php';
include_once '/auto_gourcer/src/Git.php';
include_once '/auto_gourcer/src/Gource.php';

use \dje\AutoGourcer\Git;
use \dje\AutoGourcer\Gource;
use \dje\AutoGourcer\AutoGourcer;


$gitClass  = new Git();
$gitClass->setHost('<creds_here')->setUser('<creds_here')->setPass('<creds_here')->setOrg('<creds_here');

$gourceClass = new Gource();
# Optional changes to Gource CLI options
$gourceClass->setFramerate('60')->setResolution('1920x1080')->setStartDate('2017-01-01');

$ag = new AutoGourcer();
$ag->setCount(10);

$ag->setGit($gitClass)->setGource($gourceClass)->run();
