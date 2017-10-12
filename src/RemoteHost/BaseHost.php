<?php
declare(strict_types=1);
namespace dje\AutoGourcer\RemoteHost;

/**
 * Class BitBucket
 * @package dje\AutoGourcer\RemoteHost
 */
class BaseHost
{
    /**
     *
     */
    public const HOST ='';

    /**
     *
     */
    public const NAME = '';

    /**
     * @var string
     */
    protected $protocol = 'https';

    /**
     * @var string
     */
    protected $user = '';

    /**
     * @var string
     */
    protected $pass = '';

    /**
     * @var string
     */
    protected $org  = '';

    // Getter / Setters

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
        return $this->pass;
    }

    /**
     * @return string
     */
    public function getOrg(): string
    {
        return $this->org;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $param
     * @return BitBucket
     */
    public function setUser(string $param): self
    {
        $this->user = $param;

        return $this;
    }

    /**
     * @param string $param
     * @return BitBucket
     */
    public function setPass(string $param): self
    {
        $this->Pass = $param;

        return $this;
    }

    /**
     * @param string $param
     * @return BitBucket
     */
    public function setOrg(string $param): self
    {
        $this->org = $param;

        return $this;
    }

    /**
     * @param string $param
     * @return BitBucket
     */
    public function setProtocol(string $param): self
    {
        $this->protocol = $param;

        return $this;
    }
}
