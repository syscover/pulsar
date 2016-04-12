<?php namespace Syscover\Pulsar\Libraries;

use Fetch\Server;

class ImapServices
{
    private $server;

    public function __construct($args)
    {
        if(isset($args['ssl']))
            // true por defecto
            Server::$sslEnable = $args['ssl'];

        $this->server = new Server($args['host'], $args['port']);
        $this->server->setAuthentication($args['user'], $args['password']);
    }

    public function getServer()
    {
        return $this->server;
    }
}