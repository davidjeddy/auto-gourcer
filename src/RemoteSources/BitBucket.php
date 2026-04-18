<?php
declare(strict_types=1);
namespace dje\AutoGourcer\RemoteSources;

// classes to interface with BitBucket's API easily

use \Bitbucket\API\User\Repositories;
use \Bitbucket\API\Authentication\Basic;

/**
 * Class BitBucket
 * @package dje\AutoGourcer\remote_sources
 */
class BitBucket extends BaseHost
{
    /**
     * Remote host base URL
     */
    public const HOST = 'bitbucket.com';

    /**
     * @throws \Exception
     */
    protected function basicAuth()
    {
        try {
            // the output of this if()... block should be a json string
            $this->remote = new Repositories();
            $this->remote->setCredentials(new Basic ($this->user, $this->pass));

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
    public function getRepoList()
    {
        try {
            $this->basicAuth();

            return $this->remote->get()->getContent();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
