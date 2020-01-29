<?php
/**
 * Feature
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Entities;

use Minds\UnleashClient\Exceptions\InvalidStrategyImplementationException;

class Feature
{
    /** @var string */
    protected $name = '';

    /** @var string */
    protected $description = '';

    /** @var bool */
    protected $enabled = false;

    /** @var Strategy[] */
    protected $strategies = [];

    /**
     * Gets the feature name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the feature name
     * @param string $name
     * @return Feature
     */
    public function setName(string $name): Feature
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the feature description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the feature description
     * @param string $description
     * @return Feature
     */
    public function setDescription(string $description): Feature
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets the feature enabled flag
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Sets the enabled flag for the feature
     * @param bool $enabled
     * @return Feature
     */
    public function setEnabled(bool $enabled): Feature
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Gets the feature strategies
     * @return Strategy[]
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * Sets the feature strategies
     * @param Strategy[] $strategies
     * @return Feature
     * @throws InvalidStrategyImplementationException
     */
    public function setStrategies(array $strategies): Feature
    {
        foreach ($strategies as $strategy) {
            if (!($strategy instanceof Strategy)) {
                throw new InvalidStrategyImplementationException(sprintf("Strategy should be an instance of %s", Strategy::class));
            }
        }

        $this->strategies = $strategies;
        return $this;
    }
}
