<?php

namespace Minds\UnleashClient\Cache;

use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;
use Zend\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\StorageFactory;

class SimpleCache extends SimpleCacheDecorator
{
    /** @var Logger */
    protected $logger;

    public function __construct(
        LoggerInterface $logger = null
    ) {
        // Setup local dependencies
        $this->logger = $logger ?: new Logger();

        // Instantiate cache
        parent::__construct($this->buildAdapter());
    }

    /**
     * Builds the cache storage adapter
     *
     * @return StorageInterface
     */
    protected function buildAdapter(): StorageInterface
    {
        $this->logger->debug('Building cache storage adapter');

        // Build a simple filesystem based cache

        $cache = StorageFactory::adapterFactory('filesystem');
        $serializer = StorageFactory::pluginFactory('serializer');

        $cache->addPlugin($serializer);

        return $cache;
    }
}
