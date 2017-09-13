<?php
include_once './AutoGourcer.php';
include_once './Git.php';
include_once './Gource.php';

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
$ag = new \davidjeddy\AutoGourcer\AutoGourcer(
    ['repoCount' => 5],
    new \davidjeddy\AutoGourcer\Git(),
    new \davidjeddy\AutoGourcer\Gource()
);

$ag->run();
