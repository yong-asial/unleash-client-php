<?php

namespace spec\Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\StrategyAlgorithms\ApplicationHostnameStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class ApplicationHostnameStrategyAlgorithmSpec extends ObjectBehavior
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
        $this->shouldHaveType(ApplicationHostnameStrategyAlgorithm::class);
    }

    public function it_should_check_it_is_enabled(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'hostNames' => 'foo.bar, phpspec.test, minds.com'
            ]);

        $context->getHostName()
            ->shouldBeCalled()
            ->willReturn('phpspec.test');

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_not_enabled(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'hostNames' => 'foo.bar, phpspec.test, minds.com'
            ]);

        $context->getHostName()
            ->shouldBeCalled()
            ->willReturn('notawhitelisteddomain.com');

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }
}
