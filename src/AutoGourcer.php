<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

include_once ('./vendor/autoload.php');

class AutoGourcer
{
    /**
     * @var string
     */
    public $basePath = '/auto_gourcer';

    /**
     * @var int
     */
    public $repoCount = 1;

    /**
     * @var array|\Buzz\Message\MessageInterface
     */
    private $repoData = [];

    /**
     * AutoGourcer constructor.
     * @param null $basePath
     */
    public function __construct($basePath = null)
    {
        if ($basePath !== null) {
            // reset basePath is provided
            $this->basePath = $basePath;
        }

        $dotenv = new \Dotenv\Dotenv($this->basePath . "/.env");

        $user = getenv('USER');
        $pass = getenv('PASS');

        if (getenv('HOST') === 'BitBucket') {
            $dk = new \Bitbucket\API\User\Repositories();
            $dk->setCredentials( new \Bitbucket\API\Authentication\Basic($user, $pass) );
            $responseData = (array)$dk->get();

            if (!$responseData) {
                // print response out to log file
                exec($responseData . " 2>> " . $this->basePath . "/logs/repos.json");
            }
        }

        if (empty($responseData)) {
            // if no response from remote, use logged data
            $responseData = \json_decode(file_get_contents($this->basePath . "/logs/repos.json"));
        }

        usort($responseData, "reverseOrderSort");

        $this->repoData = $responseData;

        return $this;
    }

    /**
     * Run the auto gourcer process
     */
    public function execute()
    {
        $this->cloneRepos();

        $this->gourceRender();
    }

    /**
     * @return $this
     */
    private function cloneRepos()
    {
        // clone remote repo onto local FS
        $gitClass = new \davidjeddy\AutoGourcer\Git();
        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) { break; }

            echo "Repo to clone is {$this->repoData[$i]['slug']}.\n";

            // replace with .env values
            $url = 'https://David_Eddy:Asdf1234@bitbucket.org/Sourcetoad/' . $this->repoData[$i]['slug'] . '.git';

            // clone repo
            echo "URI is {$url}.\nSlug is {$this->repoData[$i]['slug']}.\n";
            $gitClass->clone($url, $this->repoData[$i]['slug']);

            $gitClass->checkout("/auto_gourcer/repos/{$this->repoData[$i]['slug']}");
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function gourceRender()
    {
        // now render the repo using vfb and gource
        $gourceClass = new \davidjeddy\AutoGourcer\Gource();
        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) {
                break;
            }

            echo "Render counter: {$i}.\n";
            echo "Repo slug is {$this->repoData[$i]['slug']}.\n";
            echo "Last updated on {$this->repoData[$i]['utc_last_updated']}.\n";

            $gourceClass->slug = $this->repoData[$i]['slug'];

            if ($gourceClass->doesNewRenderExist() === false) {
                echo 'Rendering video for ' . $gourceClass->slug . ".\n";
                $gourceClass->render();
            }
        }

        return $this;
    }

    /**
     * @param $a array
     * @param $b array
     * @return int
     */
    function reverseOrderSort($a,$b)
    {
        return ($a['utc_last_updated'] <= $b['utc_last_updated']) ? 1 : -1;
    }
}
