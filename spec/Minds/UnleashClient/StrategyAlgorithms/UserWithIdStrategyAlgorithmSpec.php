<?php

namespace spec\Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\StrategyAlgorithms\UserWithIdStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class UserWithIdStrategyAlgorithmSpec extends ObjectBehavior
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
        $this->shouldHaveType(UserWithIdStrategyAlgorithm::class);
    }

    public function it_should_check_it_is_enabled_by_user_id(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'userIds' => '1000,1005, 1010'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('1005');

        $context->getUserGroups()
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_enabled_by_user_group(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'userIds' => '1000,1005, 1010, %admin,%tester'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('4321');

        $context->getUserGroups()
            ->shouldBeCalled()
            ->willReturn(['admin', 'pro']);

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
                'userIds' => '1000,1005, 1010, %admin'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('7777');


        $context->getUserGroups()
            ->shouldBeCalled()
            ->willReturn([]);

        $context->getUserGroups()
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }
}
