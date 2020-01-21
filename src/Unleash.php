<?php

namespace Minds\UnleashClient;

use Exception;
use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Feature;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Unleash integration with a context
 * @package Minds\UnleashClient
 */
class Unleash
{
    /** @var Logger|LoggerInterface */
    protected $logger;

    /** @var StrategyResolver */
    protected $strategyResolver;

    /** @var Repository */
    protected $repository;

    /** @var Context */
    protected $context;

    /**
     * Unleash constructor.
     * @param Config|null $config
     * @param LoggerInterface|null $logger
     * @param StrategyResolver|null $strategyResolver
     * @param CacheInterface|null $cache
     * @param Client|null $client
     * @param Repository|null $repository
     */
    public function __construct(
        Config $config = null,
        LoggerInterface $logger = null,
        StrategyResolver $strategyResolver = null,
        CacheInterface $cache = null,
        Client $client = null,
        Repository $repository = null
    ) {
        $config = $config ?: new Config();
        $this->logger = $logger ?: new Logger();
        $this->strategyResolver = $strategyResolver ?: new StrategyResolver($this->logger);
        $repository = $repository ?: new Repository(
            $config,
            $this->logger,
            $cache ?: new Cache\SimpleCache($this->logger),
            $client ?: new Client($config, $this->logger)
        );

        $this->repository = $repository;
    }

    /**
     * Sets the context for the upcoming feature flag checks
     * @param Context $context
     * @return Unleash
     */
    public function setContext(Context $context): Unleash
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Resolves a feature flag for the current context
     * @param string $featureName
     * @param bool $default
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isEnabled(string $featureName, bool $default = false): bool
    {
        try {
            $this->logger->debug("Checking for {$featureName}");

            $features = $this->repository
                ->getList();

            if (!isset($features[$featureName])) {
                $this->logger->debug("{$featureName} is not set, returning default");
                return $default;
            }

            /** @var Feature $feature */
            $feature = $features[$featureName];

            return
                $feature->isEnabled() &&
                $this->strategyResolver->isEnabled(
                    $feature->getStrategies(),
                    $this->context
                );
        } catch (Exception $e) {
            $this->logger->error($e);
            return false;
        }
    }

    /**
     * Resolves and exports the whole set of feature flags for the current context
     * @return array
     * @throws InvalidArgumentException
     */
    public function export(): array
    {
        try {
            $this->logger->debug("Exporting all features");

            $features = $this->repository
                ->getList();

            $export = [];

            foreach ($features as $featureName => $feature) {
                /** @var Feature $feature */
                $export[$featureName] =
                    $feature->isEnabled() &&
                    $this->strategyResolver->isEnabled(
                        $feature->getStrategies(),
                        $this->context
                    );
            }

            return $export;
        } catch (Exception $e) {
            $this->logger->error($e);
            return [];
        }
    }
}
