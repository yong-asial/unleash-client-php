<?php

namespace spec\Minds\UnleashClient;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Factories\StrategyAlgorithmFactory;
use Minds\UnleashClient\StrategyAlgorithms\StrategyAlgorithm;
use Minds\UnleashClient\StrategyResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class StrategyResolverSpec extends ObjectBehavior
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var StrategyAlgorithmFactory */
    protected $strategyAlgorithmFactory;

    public function let(
        LoggerInterface $logger,
        StrategyAlgorithmFactory $strategyAlgorithmFactory
    ) {
        $this->logger = $logger;
        $this->strategyAlgorithmFactory = $strategyAlgorithmFactory;

        $this->beConstructedWith(
            $logger,
            $strategyAlgorithmFactory
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(StrategyResolver::class);
    }

    public function it_should_return_if_is_not_enabled_when_empty(
        Context $context
    ) {
        $this
            ->isEnabled([], $context)
            ->shouldReturn(false);
    }

    public function it_should_return_if_is_not_enabled(
        Context $context,
        Strategy $strategy1,
        Strategy $strategy2,
        StrategyAlgorithm $strategyAlgorithm1,
        StrategyAlgorithm $strategyAlgorithm2
    ) {
        $this->strategyAlgorithmFactory->build($strategy1)
            ->shouldBeCalled()
            ->willReturn($strategyAlgorithm1);

        $this->strategyAlgorithmFactory->build($strategy2)
            ->shouldBeCalled()
            ->willReturn($strategyAlgorithm2);

        $strategyAlgorithm1->isEnabled($strategy1, $context)
            ->shouldBeCalled()
            ->willReturn(false);

        $strategyAlgorithm2->isEnabled($strategy2, $context)
            ->shouldBeCalled()
            ->willReturn(false);

        $this
            ->isEnabled([
                $strategy1,
                $strategy2,
            ], $context)
            ->shouldReturn(false);
    }

    public function it_should_return_if_is_enabled(
        Context $context,
        Strategy $strategy1,
        Strategy $strategy2,
        StrategyAlgorithm $strategyAlgorithm1,
        StrategyAlgorithm $strategyAlgorithm2
    ) {
        $this->strategyAlgorithmFactory->build($strategy1)
            ->shouldBeCalled()
            ->willReturn($strategyAlgorithm1);

        $this->strategyAlgorithmFactory->build($strategy2)
            ->shouldNotBeCalled();

        $strategyAlgorithm1->isEnabled($strategy1, $context)
            ->shouldBeCalled()
            ->willReturn(true);

        $strategyAlgorithm2->isEnabled($strategy2, $context)
            ->shouldNotBeCalled();

        $this
            ->isEnabled([
                $strategy1,
                $strategy2,
            ], $context)
            ->shouldReturn(true);
    }
}
