<?php
/**
 * Repository
 *
 * @author edgebal
 */

namespace Minds\UnleashClient;

use Exception;
use Minds\UnleashClient\Factories\FeatureFactory;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Retrieve feature flag set from either the Unleash server (using a client) or from cache
 * @package Minds\UnleashClient
 */
class Repository
{
    /** @var string */
    const UNLEASH_CACHE_PREFIX = '_unleash';

    /** @var Config */
    protected $config;

    /** @var Logger|LoggerInterface */
    protected $logger;

    /** @var Cache\SimpleCache|CacheInterface */
    protected $cache;

    /** @var Client */
    protected $client;

    /** @var FeatureFactory */
    protected $featureFactory;

    /** @var bool */
    protected $isClientRegistered = false;

    /**
     * Repository constructor.
     * @param Config|null $config
     * @param LoggerInterface|null $logger
     * @param CacheInterface|null $cache
     * @param Client|null $client
     * @param FeatureFactory|null $featureFactory
     */
    public function __construct(
        $config = null,
        $logger = null,
        $cache = null,
        $client = null,
        $featureFactory = null
    ) {
        $this->config = $config ?: new Config();
        $this->logger = $logger ?: new Logger();
        $this->cache = $cache ?: new Cache\SimpleCache($this->logger);
        $this->client = $client ?: new Client($this->config, $this->logger);
        $this->featureFactory = $featureFactory ?: new FeatureFactory();
    }

    /**
     * Returns the complete list of features from the server as objects
     * @return array
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function getList(): array
    {
        $data = $this->cache->get($this->buildCacheKey());

        if (!$data || $data['expires'] <= time()) {
            if (!$this->isClientRegistered) {
                $this->logger->debug('Client is not registered');
                $this->isClientRegistered = $this->client->register();

                if (!$this->isClientRegistered) {
                    throw new Exception('Could not register client');
                }
            }

            $this->logger->debug('Fetching feature flags from server');
            $features = $this->client->getFeatures();
            $expires = time() + $this->config->getPollingIntervalSeconds();

            $data = [
                'features' => $features,
                'expires' => $expires
            ];

            $this->logger->debug('Cache will timeout at ' . date('c', $expires));
            $this->cache->set($this->buildCacheKey(), $data);
        }

        $features = [];

        foreach ($data['features'] as $featureDto) {
            $feature = $this->featureFactory->build($featureDto);
            $features[$feature->getName()] = $feature;
        }

        return $features;
    }

    /**
     * Builds a config-aware cache key
     * @return string
     */
    protected function buildCacheKey(): string
    {
        return static::UNLEASH_CACHE_PREFIX . $this->client->getId();
    }
}
