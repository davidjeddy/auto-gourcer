<?php
declare(strict_types=1);
namespace davidjeddy\AutoGourcer;

include_once '../vendor/autoload.php';

class AutoGourcer
{
    /**
     * @var string
     */
    public $basePath = '/auto_gourcer';

    /**
     * @var array|false|string
     */
    private $host = '';

    /**
     * @var int
     */
    public $repoCount = 1;

    /**
     * @var array|\Buzz\Message\MessageInterface
     */
    private $repoData = [];

    /**
     * @var array
     */
    private $depLib = [];

    /**
     * AutoGourcer constructor.
     * @param null $basePath
     */
    public function __construct(
        $configArray = [],
        \davidjeddy\AutoGourcer\Git $gitClass,
        \davidjeddy\AutoGourcer\Gource $gourceClass
    ) {
        // load ENV handler
        $dotenv = new \Dotenv\Dotenv('../');
        $dotenv->load();

        $this->host = strtolower(getenv('HOST'));
        echo "Host is {$this->host}.\n";

        // class properties
        foreach ($configArray as $key => $value) {
            $this->{$key} = $value;
        }

        // dependant classes
        $this->depLib['Git']    = new $gitClass();
        $this->depLib['Gource'] = new $gourceClass();
    }

    /**
     * Execute the actual logic to get `stuff` done
     */
    public function run()
    {
        $repoUrl = '';

        $this->getRepoList();

        for ($i = 0; $i < $this->repoCount; $i++) {
            if ($i > $this->repoCount) {
                break;
            }

            if ($this->host === 'bitbucket.org') {
                // replace with .env values
                $repoUrl = "https://" . getenv('GITUSER') . ":" . getenv('GITPASS') ."@" .getenv('HOST') . "/" . getenv('ORG') . "/{$this->repoData[$i]['slug']}.git";
            }

            // Git commands
            $this->depLib['Git']->clone($repoUrl, $this->repoData[$i]['slug']);
            $this->depLib['Git']->checkout("{$this->basePath}/repos/{$this->repoData[$i]['slug']}");

            // Gourcer commands
            $filePath = "{$this->basePath}/renders/{$this->repoData[$i]['slug']}.mp4";
            if ($this->depLib['Gource']->doesNewRenderExist($filePath) === false) {
                $this->depLib['Gource']->slug = $this->repoData[$i]['slug'];
                $this->depLib['Gource']->render();
            }
        }
    }

    /**
     * @return $this
     */
    public function getRepoList()
    {
        // the output of this if()... block should be a json string
        if ($this->host === 'bitbucket.org') {
            $bbr = new \Bitbucket\API\User\Repositories();
            $bbr->setCredentials( new \Bitbucket\API\Authentication\Basic (getenv('BBUSER'), getenv('BBPASS')) );
            $this->repoData = $bbr->get()->getContent();
        }

        if (empty($this->repoData)) {
            $this->repoData = $this->emptyHostResponse();
        }

        return $this->jsonDecode()->sortArray();
    }

    // private methods

    /**
     * @param string $repoFile
     * @return bool|string
     */
    private function emptyHostResponse(string $repoFile = 'repos.json')
    {
        if (file_exists("{$this->basePath}/logs/{$repoFile}")) {
            // if no response from remote, use logged data
            return \file_get_contents("{$this->basePath}/logs/{$repoFile}");
        }

        return '';
    }

    /**
     * @return AutoGourcer
     * @throws \Exception
     */
    private function jsonDecode(): self
    {
        $this->repoData = \json_decode($this->repoData, true);

        if (\json_last_error()) {
            throw new \Exception(\json_last_error_msg());
        }

        return $this;
    }

    /**
     * @return AutoGourcer
     * @throws \Exception
     */
    private function sortArray(): self
    {
        try {
            usort($this->repoData, "sortInReverseOrder");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this;
    }
}
