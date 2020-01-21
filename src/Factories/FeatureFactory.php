<?php
/**
 * FeatureFactory
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Factories;

use Exception;
use Minds\UnleashClient\Entities\Feature;

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
     * @throws Exception
     */
    public function build(array $data)
    {
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
