<?php

namespace spec\Minds\UnleashClient;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Exceptions\InvalidFeaturesArrayException;
use Minds\UnleashClient\Exceptions\NoContextException;
use Minds\UnleashClient\Repository;
use Minds\UnleashClient\StrategyResolver;
use Minds\UnleashClient\Unleash;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class UnleashSpec extends ObjectBehavior
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var StrategyResolver */
    protected $strategyResolver;

    /** @var Repository */
    protected $repository;

    public function let(
        LoggerInterface $logger,
        StrategyResolver $strategyResolver
    ) {
        $this->logger = $logger;
        $this->strategyResolver = $strategyResolver;

        $this->beConstructedWith(
            $logger,
            $strategyResolver,
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Unleash::class);
    }

    public function it_should_return_if_is_enabled(
        Context $context,
        Feature $feature1,
        Strategy $strategy1
    ) {
        $feature1->isEnabled()
            ->shouldBeCalled()
            ->willReturn(true);

        $feature1->getStrategies()
            ->shouldBeCalled()
            ->willReturn([$strategy1]);

        $this->strategyResolver->isEnabled([$strategy1], $context)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setFeatures([
                'feature1' => $feature1
            ])
            ->setContext($context)
            ->isEnabled('feature1', false)
            ->shouldReturn(true);
    }

    public function it_should_return_false_if_not_exists_during_is_enabled(
        Context $context,
        Feature $feature1
    ) {
        $this
            ->setFeatures([
                'feature1' => $feature1
            ])
            ->setContext($context)
            ->isEnabled('feature2', false)
            ->shouldReturn(false);
    }

    public function it_should_throw_during_is_enabled_if_no_features_set(
        Context $context
    ) {
        $this
            ->setContext($context)
            ->shouldThrow(InvalidFeaturesArrayException::class)
            ->duringIsEnabled('feature1', false);
    }

    public function it_should_throw_during_is_enabled_if_no_context_set(
        Feature $feature1
    ) {
        $this
            ->setFeatures([
                'feature1' => $feature1
            ])
            ->shouldThrow(NoContextException::class)
            ->duringIsEnabled('feature1', false);
    }

    public function it_should_export(
        Context $context,
        Feature $feature1,
        Feature $feature2,
        Strategy $strategy1,
        Strategy $strategy2
    ) {
        $feature1->isEnabled()
            ->shouldBeCalled()
            ->willReturn(true);

        $feature2->isEnabled()
            ->shouldBeCalled()
            ->willReturn(true);

        $feature1->getStrategies()
            ->shouldBeCalled()
            ->willReturn([$strategy1]);

        $feature2->getStrategies()
            ->shouldBeCalled()
            ->willReturn([$strategy2]);

        $this->strategyResolver->isEnabled([$strategy1], $context)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->strategyResolver->isEnabled([$strategy2], $context)
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->setFeatures([
                'feature1' => $feature1,
                'feature2' => $feature2,
            ])
            ->setContext($context)
            ->export()
            ->shouldReturn([
                'feature1' => true,
                'feature2' => false,
            ]);
    }

    public function it_should_throw_during_export_if_no_features_set(
        Context $context
    ) {
        $this
            ->setContext($context)
            ->shouldThrow(InvalidFeaturesArrayException::class)
            ->duringExport();
    }

    public function it_should_throw_during_export_if_no_context_set(
        Feature $feature1
    ) {
        $this
            ->setFeatures([
                'feature1' => $feature1
            ])
            ->shouldThrow(NoContextException::class)
            ->duringExport();
    }
}
