<?php

namespace spec\Minds\UnleashClient;

use Exception;
use Minds\UnleashClient\Client;
use Minds\UnleashClient\Config;
use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Repository;
use Minds\UnleashClient\StrategyResolver;
use Minds\UnleashClient\Unleash;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class UnleashSpec extends ObjectBehavior
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var StrategyResolver */
    protected $strategyResolver;

    /** @var Repository */
    protected $repository;

    public function let(
        Config $config,
        LoggerInterface $logger,
        StrategyResolver $strategyResolver,
        CacheInterface $cache,
        Client $client,
        Repository $repository
    ) {
        $this->logger = $logger;
        $this->strategyResolver = $strategyResolver;
        $this->repository = $repository;

        $this->beConstructedWith(
            $config,
            $logger,
            $strategyResolver,
            $cache,
            $client,
            $repository
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
        $this->repository->getList()
            ->shouldBeCalled()
            ->willReturn([
                'feature1' => $feature1
            ]);

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
            ->setContext($context)
            ->isEnabled('feature1', false)
            ->shouldReturn(true);
    }

    public function it_should_return_false_if_not_exists_during_is_enabled(
        Context $context,
        Feature $feature1
    ) {
        $this->repository->getList()
            ->shouldBeCalled()
            ->willReturn([
                'feature1' => $feature1
            ]);

        $this
            ->setContext($context)
            ->isEnabled('feature2', false)
            ->shouldReturn(false);
    }

    public function it_should_return_false_if_throws_during_is_enabled(
        Context $context
    ) {
        $this->repository->getList()
            ->shouldBeCalled()
            ->willThrow(new Exception());

        $this
            ->setContext($context)
            ->isEnabled('feature1', false)
            ->shouldReturn(false);
    }

    public function it_should_export(
        Context $context,
        Feature $feature1,
        Feature $feature2,
        Strategy $strategy1,
        Strategy $strategy2
    ) {
        $this->repository->getList()
            ->shouldBeCalled()
            ->willReturn([
                'feature1' => $feature1,
                'feature2' => $feature2,
            ]);

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
            ->setContext($context)
            ->export()
            ->shouldReturn([
                'feature1' => true,
                'feature2' => false,
            ]);
    }

    public function it_should_return_empty_if_throws_during_export(
        Context $context
    ) {
        $this->repository->getList()
            ->shouldBeCalled()
            ->willThrow(new Exception());

        $this
            ->setContext($context)
            ->export()
            ->shouldReturn([]);
    }
}
