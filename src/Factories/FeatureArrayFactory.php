<?php
/**
 * FeatureArrayFactory
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Factories;

use Minds\UnleashClient\Exceptions\InvalidFeatureNameException;
use Minds\UnleashClient\Exceptions\InvalidStrategyImplementationException;

class FeatureArrayFactory
{
    /**
     * @var FeatureFactory
     */
    protected $featureFactory;

    /**
     * FeatureArrayFactory constructor.
     * @param FeatureFactory $featureFactory
     */
    public function __construct(
        $featureFactory = null
    ) {
        $this->featureFactory = $featureFactory ?: new FeatureFactory();
    }

    /**
     * Builds a Feature array based on Unleash JSON response
     * @param array $data
     * @return array
     * @throws InvalidFeatureNameException
     * @throws InvalidStrategyImplementationException
     */
    public function build(array $data): array
    {
        $features = [];

        foreach ($data as $featureData) {
            $feature = $this->featureFactory->build($featureData);
            $features[$feature->getName()] = $feature;
        }

        return $features;
    }
}
