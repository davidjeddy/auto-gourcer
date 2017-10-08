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
     * Remote host base URL
     */
    protected const remoteHostBaseURL = null;

    /**
     * @var null
     */
    protected $user = null;

    /**
     * @var null
     */
    protected $pass = null;

    /**
     * @var null
     */
    protected $org = null;

    /**
     * @var null
     */
    protected $remote = null;

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
     * @return $this
     */
    public function setUser(string $var): self
    {
        $this->user = $var;

        return $this;
    }

    /**
     * @param string $var
     * @return $this
     */
    public function setPass(string $var): self
    {
        $this->pass = $var;

        return $this;
    }

    /**
     * @param string $paramData
     * @return BaseHost
     */
    public function setOrg(string $paramData): self
    {
        $this->org = $paramData;

        return $this;
    }

    /**
     * @return BaseHost
     */
    public function getUser(): self
    {
        return $this->user;
    }

    /**
     * @return BaseHost
     */
    public function getPass(): string
    {
        return '[REDACTED FOR SECURITY]';
    }

    /**
     * @return BaseHost
     */
    public function getOrg(): self
    {
        return $this->org;
    }
}
