<?php

namespace spec\Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Helpers\NormalizedValue;
use Minds\UnleashClient\StrategyAlgorithms\FlexibleRolloutStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class FlexibleRolloutStrategyAlgorithmSpec extends ObjectBehavior
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
        $this->shouldHaveType(FlexibleRolloutStrategyAlgorithm::class);
    }

    public function it_should_check_it_is_enabled_with_user_id_stickiness(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'userId',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('1000');

        $context->getSessionId()
            ->willReturn(null);

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->build('1000', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_not_enabled_with_user_id_stickiness(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'userId',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('1000');

        $context->getSessionId()
            ->willReturn(null);

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->build('1000', 'test')
            ->shouldBeCalled()
            ->willReturn(90);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }

    public function it_should_check_it_is_enabled_with_session_id_stickiness(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'sessionId',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->willReturn(null);

        $context->getSessionId()
            ->shouldBeCalled()
            ->willReturn('phpspec~123123');

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->build('phpspec~123123', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_not_enabled_with_session_id_stickiness(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'sessionId',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->willReturn(null);

        $context->getSessionId()
            ->shouldBeCalled()
            ->willReturn('phpspec~123123');

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->build('phpspec~123123', 'test')
            ->shouldBeCalled()
            ->willReturn(90);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }

    public function it_should_check_it_is_enabled_with_random_stickiness(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'random',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->willReturn(null);

        $context->getSessionId()
            ->willReturn(null);

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->random(999999, 1)
            ->shouldBeCalled()
            ->willReturn(99);

        $this->normalizedValue->build('99', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_not_enabled_with_random_stickiness(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'random',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->willReturn(null);

        $context->getSessionId()
            ->willReturn(null);

        $this->normalizedValue->random(999999, 1)
            ->shouldBeCalled()
            ->willReturn(99);

        $this->normalizedValue->build('99', 'test')
            ->shouldBeCalled()
            ->willReturn(90);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(false);
    }

    public function it_should_check_it_is_enabled_with_default_stickiness_using_user_id(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'default',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->shouldBeCalled()
            ->willReturn('1000');

        $context->getSessionId()
            ->willReturn(null);

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->build('1000', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_enabled_with_default_stickiness_using_session_id(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'default',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->willReturn(null);

        $context->getSessionId()
            ->shouldBeCalled()
            ->willReturn('phpspec~123123');

        $this->normalizedValue->random(999999, 1)
            ->willReturn(99);

        $this->normalizedValue->build('phpspec~123123', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }

    public function it_should_check_it_is_enabled_with_default_stickiness_using_random(
        Strategy $strategy,
        Context $context
    ) {
        $strategy->getParameters()
            ->shouldBeCalled()
            ->willReturn([
                'rollout' => 20,
                'stickiness' => 'default',
                'groupId' => 'test'
            ]);

        $context->getUserId()
            ->willReturn(null);

        $context->getSessionId()
            ->willReturn(null);

        $this->normalizedValue->random(999999, 1)
            ->shouldBeCalled()
            ->willReturn(99);

        $this->normalizedValue->build('99', 'test')
            ->shouldBeCalled()
            ->willReturn(10);

        $this
            ->isEnabled($strategy, $context)
            ->shouldReturn(true);
    }
}
