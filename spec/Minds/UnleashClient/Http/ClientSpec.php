<?php

namespace spec\Minds\UnleashClient;

use Minds\UnleashClient\Http\Client;
use Minds\UnleashClient\Http\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class ClientSpec extends ObjectBehavior
{
    private const TEST_URL = "test_url";
    private const TEST_APPLICATION_NAME = "test_application_name";
    private const TEST_INSTANCE_ID = "test_instance_id";
    private const TEST_POLLING_INTERVAL_SECONDS = 15;
    private const TEST_METRICS_INTERVAL_SECONDS = 20;

    /** @var Config; */
    private $config;

    /** @var LoggerInterface */
    private $logger;

    /** @var HttpClient */
    private $httpClient;

    public function let(
        Config $config,
        LoggerInterface $logger,
        HttpClient $httpClient
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->httpClient = $httpClient;

        $this->config->getApiUrl()->willReturn(ClientSpec::TEST_URL);
        $this->config->getApplicationName()->willReturn(ClientSpec::TEST_APPLICATION_NAME);
        $this->config->getInstanceId()->willReturn(ClientSpec::TEST_INSTANCE_ID);
        $this->config->getPollingIntervalSeconds()->willReturn(ClientSpec::TEST_POLLING_INTERVAL_SECONDS);
        $this->config->getMetricsIntervalSeconds()->willReturn(ClientSpec::TEST_METRICS_INTERVAL_SECONDS);

        $this->beConstructedWith($this->config, $this->logger, $this->httpClient);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function it_should_register(ResponseInterface $response)
    {
        $date = date("c");
        $payload = [
            'appName' => ClientSpec::TEST_APPLICATION_NAME,
            'instanceId' => ClientSpec::TEST_INSTANCE_ID,
            'sdkVersion' => "unleash-client-php:" . Config::VERSION,
            'strategies'=> [],
            'started' => $date,
            'interval' => ClientSpec::TEST_METRICS_INTERVAL_SECONDS * 1000
        ];
        $this->httpClient->post('client/register', $payload)
            ->shouldBeCalled()
            ->willReturn($response);

        $this->register($date);
    }

    public function it_should_fetch(ResponseInterface $response)
    {
        $this->httpClient->get('client/features')
            ->shouldBeCalled()
            ->willReturn($response);

        $response->getStatusCode()
            ->shouldBeCalled()
            ->willReturn(200);

        $response->getBody()
            ->shouldBeCalled()
            ->willReturn($this->getMockData('features.json'));

        $features = $this->fetch();
        $features->shouldHaveCount(2);
    }

    private function getMockData($filename)
    {
        return file_get_contents(__DIR__."/MockData/${filename}");
    }
}
