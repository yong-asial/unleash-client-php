<?php
/**
 * Strategy
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Entities;

class Strategy
{
    /** @var string */
    protected $name = '';

    /** @var array */
    protected $parameters = [];

    /**
     * Gets the strategy name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the strategy name
     * @param string $name
     * @return Strategy
     */
    public function setName(string $name): Strategy
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the strategy parameters
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets the strategy parameters
     * @param array $parameters
     * @return Strategy
     */
    public function setParameters(array $parameters): Strategy
    {
        $this->parameters = $parameters;
        return $this;
    }
}
