<?php

namespace spec\Minds\UnleashClient;

use Minds\UnleashClient\Client;
use Minds\UnleashClient\Config;
use Minds\UnleashClient\Entities\Feature;
use Minds\UnleashClient\Factories\FeatureFactory;
use Minds\UnleashClient\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class RepositorySpec extends ObjectBehavior
{
    /** @var Config */
    protected $config;

    /** @var LoggerInterface */
    protected $logger;

    /** @var CacheInterface */
    protected $cache;

    /** @var Client */
    protected $client;

    /** @var FeatureFactory */
    protected $featureFactory;

    public function let(
        Config $config,
        LoggerInterface $logger,
        CacheInterface $cache,
        Client $client,
        FeatureFactory $featureFactory
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->client = $client;
        $this->featureFactory = $featureFactory;

        $this->beConstructedWith(
            $config,
            $logger,
            $cache,
            $client,
            $featureFactory
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    public function it_should_get_list_using_cache(
        Feature $feature1,
        Feature $feature2
    ) {
        $this->client->getId()
            ->shouldBeCalled()
            ->willReturn('test123');

        $this->cache->get(Repository::UNLEASH_CACHE_PREFIX . 'test123')
            ->shouldBeCalled()
            ->willReturn([
                'features' => [
                    [ 'name' => 'feature1' ],
                    [ 'name' => 'feature2' ],
                ],
                'expires' => time() + 1000000
            ]);

        $this->featureFactory->build([ 'name' => 'feature1' ])
            ->shouldBeCalled()
            ->willReturn($feature1);

        $this->featureFactory->build([ 'name' => 'feature2' ])
            ->shouldBeCalled()
            ->willReturn($feature2);

        $feature1->getName()
            ->shouldBeCalled()
            ->willReturn('feature1');

        $feature2->getName()
            ->shouldBeCalled()
            ->willReturn('feature2');

        $this
            ->getList()
            ->shouldReturn([
                'feature1' => $feature1,
                'feature2' => $feature2
            ]);
    }

    public function it_should_get_list_without_cache(
        Feature $feature1,
        Feature $feature2
    ) {
        $this->client->getId()
            ->shouldBeCalled()
            ->willReturn('test123');

        $this->cache->get(Repository::UNLEASH_CACHE_PREFIX . 'test123')
            ->shouldBeCalled()
            ->willReturn([
                'features' => [],
                'expires' => time() - 1000000
            ]);

        $this->client->register()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->client->getFeatures()
            ->shouldBeCalled()
            ->willReturn([
                [ 'name' => 'feature1' ],
                [ 'name' => 'feature2' ],
            ]);

        $this->config->getPollingIntervalSeconds()
            ->shouldBeCalled()
            ->willReturn(0);

        $this->cache->set(Repository::UNLEASH_CACHE_PREFIX . 'test123', Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->featureFactory->build([ 'name' => 'feature1' ])
            ->shouldBeCalled()
            ->willReturn($feature1);

        $this->featureFactory->build([ 'name' => 'feature2' ])
            ->shouldBeCalled()
            ->willReturn($feature2);

        $feature1->getName()
            ->shouldBeCalled()
            ->willReturn('feature1');

        $feature2->getName()
            ->shouldBeCalled()
            ->willReturn('feature2');

        $this
            ->getList()
            ->shouldReturn([
                'feature1' => $feature1,
                'feature2' => $feature2
            ]);
    }
}
