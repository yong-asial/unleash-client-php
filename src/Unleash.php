<?php

namespace Minds\UnleashClient;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Exceptions\InvalidFeatureImplementationException;
use Minds\UnleashClient\Exceptions\InvalidFeaturesArrayException;
use Minds\UnleashClient\Exceptions\NoContextException;
use Psr\Log\LoggerInterface;

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

    /** @var array */
    protected $features;

    /** @var Context */
    protected $context;

    /**
     * Unleash constructor.
     * @param LoggerInterface|null $logger
     * @param StrategyResolver|null $strategyResolver
     */
    public function __construct(
        LoggerInterface $logger = null,
        StrategyResolver $strategyResolver = null
    ) {
        $this->logger = $logger ?: new Logger();
        $this->strategyResolver = $strategyResolver ?: new StrategyResolver($this->logger);
    }

    /**
     * @param array $features
     * @return Unleash
     * @throws InvalidFeatureImplementationException
     */
    public function setFeatures(array $features): Unleash
    {
        foreach ($features as $key => $feature) {
            if (!($feature instanceof Feature)) {
                throw new InvalidFeatureImplementationException(sprintf("Strategy should be an instance of %s", Feature::class));
            }
        }

        $this->features = $features;
        return $this;
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
     * @param string $key
     * @param bool $default
     * @return bool
     * @throws InvalidFeaturesArrayException
     * @throws NoContextException
     */
    public function isEnabled(string $key, bool $default = false): bool
    {
        if (!is_array($this->features)) {
            throw new InvalidFeaturesArrayException();
        }

        if (!$this->context) {
            throw new NoContextException();
        }

        $this->logger->debug("Checking for {$key}");

        if (!isset($this->features[$key])) {
            $this->logger->debug("{$key} is not set, returning default");
            return $default;
        }

        /** @var Feature $feature */
        $feature = $this->features[$key];

        return
            $feature->isEnabled() &&
            $this->strategyResolver->isEnabled(
                $feature->getStrategies(),
                $this->context
            );
    }

    /**
     * Resolves and exports the whole set of feature flags for the current context
     * @return array
     * @throws InvalidFeaturesArrayException
     * @throws NoContextException
     */
    public function export(): array
    {
        if (!is_array($this->features)) {
            throw new InvalidFeaturesArrayException();
        }

        if (!$this->context) {
            throw new NoContextException();
        }

        $this->logger->debug("Exporting all features");

        $export = [];

        foreach ($this->features as $featureName => $feature) {
            /** @var Feature $feature */
            $export[$featureName] =
                $feature->isEnabled() &&
                $this->strategyResolver->isEnabled(
                    $feature->getStrategies(),
                    $this->context
                );
        }

        return $export;
    }
}
