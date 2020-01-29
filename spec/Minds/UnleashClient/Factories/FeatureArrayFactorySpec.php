<?php

namespace spec\Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Factories\FeatureArrayFactory;
use Minds\UnleashClient\Factories\FeatureFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeatureArrayFactorySpec extends ObjectBehavior
{
    /** @var FeatureFactory */
    protected $featureFactory;

    public function let(
        FeatureFactory $featureFactory
    ) {
        $this->featureFactory = $featureFactory;

        $this->beConstructedWith($featureFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FeatureArrayFactory::class);
    }

    public function it_should_build(
        Feature $feature1,
        Feature $feature2
    ) {
        $this->featureFactory->build([ 'name' => 'feature1' ])
            ->shouldBeCalled()
            ->willReturn($feature1);

        $this->featureFactory->build([ 'name' => 'feature2' ])
            ->shouldBeCalled()
            ->willReturn($feature2);

        $feature1->getName()
            ->shouldBeCalled()
            ->willReturn('feature1');

        $feature2->getName()
            ->shouldBeCalled()
            ->willReturn('feature2');

        $this
            ->build([
                [ 'name' => 'feature1' ],
                [ 'name' => 'feature2' ],
            ])
            ->shouldReturn([
                'feature1' => $feature1,
                'feature2' => $feature2,
            ]);
    }
}
