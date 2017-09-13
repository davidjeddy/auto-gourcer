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
     * Execute the actual logic to get `stuff` done
     */
    public function run()
    {
        $gitClass = new Git();
        $gourceClass = new Gource();
        $repoUrl = '';

        $this->getRepoList();
        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) {
                break;
            }

            if (getenv('HOST') === 'BitBucket') {
                // replace with .env values
                $repoUrl = "https://{getenv('user')}:{getenv('PASS')}@bitbucket.org/" . (getenv('ORG') ? getenv('ORG')
                    . '/' :  null) . "{$this->repoData[$i]['slug']}.git";
            }

            // clone repo
            $gitClass->clone($repoUrl, $this->repoData[$i]['slug']);
            $gitClass->checkout("{$this->basePath}/repos/{$this->repoData[$i]['slug']}");

            // render repo visualization
            if (!$gourceClass->doesNewRenderExist("{$this->basePath}/renders/{$this->repoData[$i]['slug']}.mp4")) {
                $gourceClass->render();
            }

        }
    }

    /**
     * @return $this
     */
    public function getRepoList()
    {
        $user = getenv('USER');
        $pass = getenv('PASS');
        $responseData = [];

        if (getenv('HOST') === 'BitBucket') {
            $bbr = new \Bitbucket\API\User\Repositories();
            $bbr->setCredentials( new \Bitbucket\API\Authentication\Basic($user, $pass) );
            $responseData = $bbr->get();
        }

        // TODO move response handling to another method, return valid array
        if (empty($responseData)) {
            // if no response from remote, use logged data
            $responseData = \file_get_contents("{$this->basePath}/logs/repos.json");
        }

        $responseData = \json_decode($responseData, true);

        if (json_last_error()) {
            echo \json_last_error_msg();
            exit(1);
        }

        usort($responseData, "sortInReverseOrder");

        $this->repoData = $responseData;

        return $this;
    }
}
