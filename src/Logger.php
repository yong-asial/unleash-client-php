<?php
namespace Minds\UnleashClient;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\ErrorLogHandler;

/**
 * Monolog wrapper for Unleash client
 * @package Minds\UnleashClient
 */
class Logger extends MonoLogger
{
    public function __construct()
    {
        parent::__construct('UnleashClient');
        $this->pushHandler(new ErrorLogHandler());
    }
}
