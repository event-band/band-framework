<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace EventBand\Transport\Amqp\Definition;

class ConnectionBuilder implements ConnectionDefinition
{
    private $builder;
    private $hosts             = ['localhost'];
    private $port              = '5672';
    private $user              = 'guest';
    private $password          = 'guest';
    private $virtualHost       = '/';
    private $insist            = false;
    private $loginMethod       = 'AMQPLAIN';
    private $loginResponse     = null;
    private $locale            = 'en_US';
    private $connectionTimeout = 3.0;
    private $readWriteTimeout  = 3.0;
    private $context           = null;
    private $keepalive         = false;
    private $heartbeat         = 0;

    public function __construct(AmqpBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function end()
    {
        return $this->builder;
    }

    public function options(array $options)
    {
        foreach ($options as $key => $value) {
            $this->$key($value);
        }

        return $this;
    }

    public function host($hosts)
    {
        $hosts = is_array($hosts) ? array_values($hosts) : explode(',', $hosts);
        if (count($hosts)) {
            $this->hosts = $hosts;
        }

        return $this;
    }

    public function getHost()
    {
        return trim($this->hosts[array_rand($this->hosts)]);
    }

    public function port($port)
    {
        $this->port = $port;

        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function user($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function password($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function virtualHost($virtualHost)
    {
        $this->virtualHost = $virtualHost;

        return $this;
    }

    public function getVirtualHost()
    {
        return $this->virtualHost;
    }

    public function heartbeat($heartbeat)
    {
        $this->heartbeat = $heartbeat;
    }

    public function getHeartbeat()
    {
        return $this->heartbeat;
    }

    public function getInsist()
    {
        return $this->insist;
    }

    public function insist($insist)
    {
        $this->insist = $insist;

        return $this;
    }

    public function getLoginMethod()
    {
        return $this->loginMethod;
    }

    public function login_method($login_method)
    {
        $this->loginMethod = $login_method;

        return $this;
    }

    public function getLoginResponse()
    {
        return $this->loginResponse;
    }

    public function login_response($login_response)
    {
        $this->loginResponse = $login_response;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function locale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getConnectionTimeout()
    {
        return $this->connectionTimeout;
    }

    public function connection_timeout($connection_timeout)
    {
        $this->connectionTimeout = $connection_timeout;

        return $this;
    }

    public function getReadWriteTimeout()
    {
        return $this->readWriteTimeout;
    }

    public function read_write_timeout($read_write_timeout)
    {
        $this->readWriteTimeout = $read_write_timeout;

        return $this;
    }

    public function getKeepalive()
    {
        return $this->keepalive;
    }

    public function keepalive($keepalive)
    {
        $this->keepalive = $keepalive;

        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function context($context)
    {
        $this->context = $context;

        return $this;
    }
}
