<?php

namespace spec\Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Factories\FeatureFactory;
use Minds\UnleashClient\Factories\StrategyFactory;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeatureFactorySpec extends ObjectBehavior
{
    /** @var StrategyFactory */
    protected $strategyFactory;

    public function let(
        StrategyFactory $strategyFactory
    ) {
        $this->strategyFactory = $strategyFactory;

        $this->beConstructedWith($strategyFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FeatureFactory::class);
    }

    public function it_should_build(
        Strategy $strategy1,
        Strategy $strategy2
    ) {
        $this->strategyFactory->build(['strategy.1'])
            ->shouldBeCalled()
            ->willReturn($strategy1);

        $this->strategyFactory->build(['strategy.2'])
            ->shouldBeCalled()
            ->willReturn($strategy2);

        $this
            ->build([
                'name' => 'phpspec',
                'description' => 'a phpspec feature',
                'enabled' => true,
                'strategies' => [
                    ['strategy.1'],
                    ['strategy.2']
                ]
            ])
            ->shouldBeAFeature([
                'name' => 'phpspec',
                'description' => 'a phpspec feature',
                'enabled' => true,
                'strategies' => [
                    $strategy1,
                    $strategy2
                ]
            ]);
    }

    public function getMatchers(): array
    {
        return [
            'beAFeature' => function ($subject, $data) {
                if (!($subject instanceof Feature)) {
                    throw new FailureException(sprintf("Subject should be a %s instance", Feature::class));
                }

                if ($subject->getName() !== $data['name']) {
                    throw new FailureException('Unexpected subject getName() value');
                }

                if ($subject->getDescription() !== $data['description']) {
                    throw new FailureException('Unexpected subject getDescription() value');
                }

                if ($subject->isEnabled() !== $data['enabled']) {
                    throw new FailureException('Unexpected subject isEnabled() value');
                }

                if ($subject->getStrategies() !== $data['strategies']) {
                    throw new FailureException('Unexpected subject getStrategies() value');
                }

                return true;
            }
        ];
    }
}
