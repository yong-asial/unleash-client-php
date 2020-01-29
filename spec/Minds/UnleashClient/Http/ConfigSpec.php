<?php

namespace spec\Minds\UnleashClient;

use Minds\UnleashClient\Http\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Config::class);
    }

    public function it_reads_from_constructed_values()
    {
        $url = "new_url";
        $instanceId = "new_instance_id";
        $applicationName = "new_application_name";
        $pollingIntervalSeconds = 100;
        $metricsIntervalSeconds = 200;
        $this->beConstructedWith($url, $instanceId, $applicationName, $pollingIntervalSeconds, $metricsIntervalSeconds);
        $this->getApiUrl()->shouldEqual($url);
        $this->getInstanceId()->shouldEqual($instanceId);
        $this->getApplicationName()->shouldEqual($applicationName);
        $this->getPollingIntervalSeconds()->shouldEqual($pollingIntervalSeconds);
        $this->getMetricsIntervalSeconds()->shouldEqual($metricsIntervalSeconds);
    }

    public function it_sets_and_gets_values()
    {
        $url = "new_url";
        $instanceId = "new_instance_id";
        $applicationName = "new_application_name";
        $pollingIntervalSeconds = 100;
        $metricsIntervalSeconds = 200;

        $this->setApiUrl($url);
        $this->setInstanceId($instanceId);
        $this->setApplicationName($applicationName);
        $this->setPollingIntervalSeconds($pollingIntervalSeconds);
        $this->setMetricsIntervalSeconds($metricsIntervalSeconds);

        $this->getApiUrl()->shouldEqual($url);
        $this->getInstanceId()->shouldEqual($instanceId);
        $this->getApplicationName()->shouldEqual($applicationName);
        $this->getPollingIntervalSeconds()->shouldEqual($pollingIntervalSeconds);
        $this->getMetricsIntervalSeconds()->shouldEqual($metricsIntervalSeconds);
    }
}
