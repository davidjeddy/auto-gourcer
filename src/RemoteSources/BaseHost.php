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
}