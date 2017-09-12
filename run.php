<?php
include_once './src/Git.php';
include_once './src/Gource.php';

$repo_count = 5;

// function exec ($command, array &$output = null, &$re turn_var = null) {}
$creds = 'david@sourcetoad.com:Asdf1234';
$repoList = 'https://api.bitbucket.org/1.0/user/repositories';
$command = "curl -u {$creds} {$repoList} --compressed --output ./logs/repos.json 2>> ./logs/curl.log";
exec($command, $responseData, $errorCode);
if ($errorCode !== 0) { exit($errorCode); }

$responseData = \json_decode(\file_get_contents('./logs/repos.json'), true);

usort($responseData, "method1");

// clone remote repo onto local FS
$gitClass = new \davidjeddy\AutoGourcer\Git();
for ($i = 0; $i < $repo_count; $i++) {
    if ($i > $repo_count) { break; }

    echo "Repo to clone is {$responseData[$i]['slug']}.\n";

    // replace with .env values
    $url = 'https://David_Eddy:Asdf1234@bitbucket.org/Sourcetoad/' . $responseData[$i]['slug'] . '.git';

    // clone repo
    echo "URI is {$url}.\nSlug is {$responseData[$i]['slug']}.\n";
    $gitClass->clone($url, $responseData[$i]['slug']);

    $gitClass->checkout("/auto_gourcer/repos/{$responseData[$i]['slug']}");
}

// now render the repo using vfb and gource
$gourceClass = new \davidjeddy\AutoGourcer\Gource();
for ($i = 0; $i < $repo_count; $i++) {
    if ($i > $repo_count) {
        break;
    }

    echo "Render counter: {$i}.\n";
    echo "Repo slug is {$responseData[$i]['slug']}.\n";
    echo "Last updated on {$responseData[$i]['utc_last_updated']}.\n";

    $gourceClass->slug = $responseData[$i]['slug'];

    if ($gourceClass->doesNewRenderExist() === false) {
        echo 'Rendering video for ' . $gourceClass->slug . ".\n";
        $gourceClass->render();
    }
}

/**
 * @param $a array
 * @param $b array
 * @return int
 */
function method1($a,$b)
{
    return ($a['utc_last_updated'] <= $b['utc_last_updated']) ? 1 : -1;
}