<?php
include_once '/auto_gourcer/src/AutoGourcer.php';
include_once '/auto_gourcer/src/Git.php';
include_once '/auto_gourcer/src/Gource.php';

use \davidjeddy\AutoGourcer\Git;
use \davidjeddy\AutoGourcer\Gource;
use \davidjeddy\AutoGourcer\AutoGourcer;

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

// DI FTW. Pass everything AG needs into the __constructor
$ag = new AutoGourcer(['repoCount' => 5], new Git(), new Gource());
$ag->run();
