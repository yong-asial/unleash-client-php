<?php
/**
 * FeatureFactory
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Exceptions\InvalidFeatureNameException;
use Minds\UnleashClient\Exceptions\InvalidStrategyImplementationException;

/**
 * Factory class to make Feature instantiation testable
 * @package Minds\UnleashClient\Factories
 */
class FeatureFactory
{
    /** @var StrategyFactory */
    protected $strategyFactory;

    /**
     * FeatureFactory constructor.
     * @param StrategyFactory|null $strategyFactory
     */
    public function __construct(
        StrategyFactory $strategyFactory = null
    ) {
        $this->strategyFactory = $strategyFactory ?: new StrategyFactory();
    }

    /**
     * Creates a new Feature instance
     * @param array $data
     * @return Feature
     * @throws InvalidFeatureNameException
     * @throws InvalidStrategyImplementationException
     */
    public function build(array $data)
    {
        if (!isset($data['name']) || !$data['name']) {
            throw new InvalidFeatureNameException();
        }

        $feature = new Feature();

        $strategies = [];

        foreach ($data['strategies'] as $strategy) {
            $strategies[] = $this->strategyFactory
                ->build($strategy);
        }

        $feature
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setEnabled($data['enabled'])
            ->setStrategies($strategies);

        return $feature;
    }
}
