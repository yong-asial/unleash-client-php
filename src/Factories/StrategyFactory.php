<?php
/**
 * StrategyFactory
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Strategy;

/**
 * Factory class to make Strategy instantiation testable
 * @package Minds\UnleashClient\Factories
 */
class StrategyFactory
{
    /**
     * Creates a new Strategy instance
     * @param array $data
     * @return Strategy
     */
    public function build(array $data)
    {
        $strategy = new Strategy();

        $strategy
            ->setName($data['name'])
            ->setParameters($data['parameters']);

        return $strategy;
    }
}
