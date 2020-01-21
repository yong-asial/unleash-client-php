<?php

namespace spec\Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Factories\StrategyAlgorithmFactory;
use Minds\UnleashClient\StrategyAlgorithms\DefaultStrategyAlgorithm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class StrategyAlgorithmFactorySpec extends ObjectBehavior
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
        $this->shouldHaveType(StrategyAlgorithmFactory::class);
    }

    public function it_should_build(
        Strategy $strategy
    ) {
        $strategy->getName()
            ->shouldBeCalled()
            ->willReturn('default');

        $this
            ->build($strategy)
            ->shouldBeAnInstanceOf(DefaultStrategyAlgorithm::class);
    }

    public function it_should_return_null_if_class_does_not_exist(
        Strategy $strategy
    ) {
        $strategy->getName()
            ->shouldBeCalled()
            ->willReturn('php~notexisting~class');

        $this->logger->warning(Argument::cetera())
            ->shouldBeCalled();

        $this
            ->build($strategy)
            ->shouldReturn(null);
    }
}
