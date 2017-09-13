<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

include_once ('../vendor/autoload.php');
include_once ('./Git.php');
include_once ('./Gource.php');

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

        new \Dotenv\Dotenv($this->basePath . "/.env");
    }

    /**
     * @return $this
     */
    public function getRepoList()
    {
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
            $responseData = \json_decode(\file_get_contents("{$this->basePath}/logs/repos.json"), true);

        }

        usort($responseData, "sortInReverseOrder");

        $this->repoData = $responseData;

        return $this;
    }

    /**
     * @return $this
     */
    public function cloneRepos()
    {
        // clone remote repo onto local FS
        $gitClass = new Git();
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
    public function gourceRender()
    {
        // now render the repo using vfb and gource
        $gourceClass = new Gource();
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
}
