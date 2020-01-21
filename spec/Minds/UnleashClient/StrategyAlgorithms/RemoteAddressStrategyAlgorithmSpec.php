<?php

namespace spec\Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\StrategyAlgorithms\RemoteAddressStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class RemoteAddressStrategyAlgorithmSpec extends ObjectBehavior
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
        $this->shouldHaveType(RemoteAddressStrategyAlgorithm::class);
    }

    public function it_should_check_it_is_enabled(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'IPs' => '10.0.0.1, 127.0.0.1, 2001:0db8:85a3:0000:0000:8a2e:0370:7334'
            ]);

        $context->getRemoteAddress()
            ->shouldBeCalled()
            ->willReturn('127.0.0.1');

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_enabled_with_ipv6_casing(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'IPs' => '10.0.0.1, 127.0.0.1, 2001:0db8:85a3:0000:0000:8a2e:0370:7334'
            ]);

        $context->getRemoteAddress()
            ->shouldBeCalled()
            ->willReturn('2001:0DB8:85A3:0000:0000:8A2E:0370:7334');

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
                'IPs' => '10.0.0.1, 127.0.0.1, 2001:0db8:85a3:0000:0000:8a2e:0370:7334'
            ]);

        $context->getRemoteAddress()
            ->shouldBeCalled()
            ->willReturn('192.168.0.200');

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }
}
