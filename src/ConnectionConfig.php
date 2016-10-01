<?php
/**
 * Created by PhpStorm.
 * User: nikolatrbojevic
 * Date: 27/09/2016
 * Time: 08:05
 */

namespace CLLibs;


/**
 * Class ConnectionConfig just holds the connection params
 * @package CLLibs
 */
class ConnectionConfig
{
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $exchange;

    /**
     * ConnectionConfig constructor.
     * @param string $host     The host to connect to.
     * @param string $port     The port to use.
     * @param string $user     The username
     * @param string $password The password (can be empty)
     * @param string $exchange Messaging exchange name.
     */
    public function __construct(string $host, string $port, string $user, string $password = null, string $exchange = null)
    {
        $this->user     = $user;
        $this->host     = $host;
        $this->port     = $port;
        $this->password = $password;
        $this->exchange = $exchange;
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
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

}