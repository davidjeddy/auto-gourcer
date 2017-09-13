<?php
include_once './AutoGourcer.php';

/**
 * @param $a
 * @param $b
 * @return int
 */
function sortInReverseOrder($a,$b)
{
    return ($a['utc_last_updated'] <= $b['utc_last_updated']) ? 1 : -1;
}

$ag = new \davidjeddy\AutoGourcer\AutoGourcer();
$ag->repoCount = 5;
$ag->run();
