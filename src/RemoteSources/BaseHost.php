<?php
declare(strict_types=1);
namespace dje\AutoGourcer\RemoteSources;

/**
 * Interface RemoteHostInterface
 * @package dje\AutoGourcer\remote_sources
 */
class BaseHost
{
    /**
     * @const string
     */
    public const HOST = '';

    /**
     * @var string
     */
    protected $user ='';

    /**
     * @var string
     */
    protected $pass ='';

    /**
     * @var string
     */
    protected $org ='';

    /**
     * @var string
     */
    protected $remote ='';

    /**
     * @var string
     */
    protected $logDir ='';

    /**
     * throw \Exception
     */
    protected function basicAuth()
    {
        throw \Exception('No basicAuth() logic defined.');
    }

    /**
     * throw \Exception
     */
    protected function getRepoList()
    {
        throw \Exception('No getRepoList() logic defined.');
    }


    /**
     * @return string
     */
    protected function emptyHostResponse(): string
    {
        // if no response from remote, use logged data
        if (\file_exists($this->getRepoListFromLogFile())) {
            return (string)file_get_contents("{$this->logDir}/auto-gourcer/repos.json");
        }

        return '';
    }

    // getter and setters

    /**
     * @param string $var
     * @return self
     */
    public function setUser(string $var): self
    {
        $this->user = $var;

        return $this;
    }

    /**
     * @param string $var
     * @return self
     */
    public function setPass(string $var): self
    {
        $this->pass = $var;

        return $this;
    }

    /**
     * @param string $paramData
     * @return self
     */
    public function setOrg(string $paramData): self
    {
        $this->org = $paramData;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return '[REDACTED FOR SECURITY]';
    }

    /**
     * @return string
     */
    public function getOrg(): string
    {
        return $this->org;
    }
}
