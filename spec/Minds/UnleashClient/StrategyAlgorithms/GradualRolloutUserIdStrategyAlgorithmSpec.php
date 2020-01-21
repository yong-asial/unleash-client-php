<?php

namespace spec\Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Helpers\NormalizedValue;
use Minds\UnleashClient\StrategyAlgorithms\GradualRolloutUserIdStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class GradualRolloutUserIdStrategyAlgorithmSpec extends ObjectBehavior
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var NormalizedValue */
    protected $normalizedValue;

    public function let(
        LoggerInterface $logger,
        NormalizedValue $normalizedValue
    ) {
        $this->logger = $logger;
        $this->normalizedValue = $normalizedValue;

        $this->beConstructedWith($logger, $normalizedValue);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GradualRolloutUserIdStrategyAlgorithm::class);
    }


    public function it_should_check_it_is_enabled(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'percentage' => 20,
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('1000');

        $this->normalizedValue->build('1000', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_no_enabled(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'percentage' => 20,
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('1000');

        $this->normalizedValue->build('1000', 'test')
            ->shouldBeCalled()
            ->willReturn(90);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }
}
