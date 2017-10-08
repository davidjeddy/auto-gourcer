<?php
declare(strict_types=1);
namespace dje\AutoGourcer\RemoteSources;

// classes to interface with GitHub's API easily
use Github\Client as GHC;

/**
 * Class GitHub
 * @package dje\AutoGourcer\remote_sources
 */
class GitHub extends BaseHost
{
    /**
     * Remote host base URL
     */
    protected const remoteHostBaseURL = 'https://github.com';

    /**
     * @throws \Exception
     */
    protected function basicAuth()
    {
        try {
            // the output of this if()... block should be a json string
            $this->remote = new Repositories();
            $this->remote->authenticate($this->user, $this->pass, GHC::AUTH_HTTP_PASSWORD);

            if (empty($returnData)) {
                throw new \Exception("No valid response from {self::remoteHostBaseURL}.\n
                Most likely cause is invalid credentials or host has gone away.");
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getRepoList()
    {
        try {
            $this->basicAuth();

            return $this->remote->api('repo')->all();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
