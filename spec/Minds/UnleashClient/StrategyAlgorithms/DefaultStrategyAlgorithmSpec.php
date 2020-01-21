<?php

namespace spec\Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\StrategyAlgorithms\DefaultStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class DefaultStrategyAlgorithmSpec extends ObjectBehavior
{
    /** @var LoggerInterface */
    protected $logger;

    public function let(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;

        $this->beConstructedWith($logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DefaultStrategyAlgorithm::class);
    }

    public function it_should_check_it_is_enabled(
        Strategy $strategy,
        Context $context
    ) {
        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }
}
