<?php
namespace Minds\UnleashClient;

/**
 * Configuration object for the Unleash client
 */
class Config
{
    /** @var string */
    public const VERSION = '0.0.1';

    /** @var string */
    protected $apiUrl;

    /** @var string */
    protected $instanceId;

    /** @var string */
    protected $applicationName;

    /** @var int */
    protected $pollingIntervalSeconds;

    /** @var int */
    protected $metricsIntervalSeconds;

    /** @var int */
    protected $cacheTtl;

    /**
     * Config constructor.
     * @param string|null $apiUrl
     * @param string|null $instanceId
     * @param string|null $applicationName
     * @param int|null $pollingIntervalSeconds
     * @param int|null $metricsIntervalSeconds
     */
    public function __construct(
        string $apiUrl = null,
        string $instanceId = null,
        string $applicationName = null,
        int $pollingIntervalSeconds = null,
        int $metricsIntervalSeconds = null
    ) {
        $this->apiUrl = $apiUrl ?? '';
        $this->instanceId = $instanceId ?? '';
        $this->applicationName = $applicationName ?? '';
        $this->pollingIntervalSeconds = $pollingIntervalSeconds ?? 15;
        $this->metricsIntervalSeconds = $metricsIntervalSeconds ?? 60;
    }

    /**
     * Gets the current version number of the library
     * @return string
     */
    public function getVersion(): string
    {
        return static::VERSION;
    }

    /**
     * Gets the Unleash server URL
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Sets the Unleash server URL
     * @param string $apiUrl
     * @return Config
     */
    public function setApiUrl(string $apiUrl): Config
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * Gets the Unleash instance ID
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * Sets the Unleash instance ID
     * @param string $instanceId
     * @return Config
     */
    public function setInstanceId(string $instanceId): Config
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    /**
     * Gets the Unleash application name
     * @return string
     */
    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    /**
     * Sets the Unleash application name
     * @param string $applicationName
     * @return Config
     */
    public function setApplicationName(string $applicationName): Config
    {
        $this->applicationName = $applicationName;
        return $this;
    }

    /**
     * Gets the Unleash polling interval in seconds
     * @return int
     */
    public function getPollingIntervalSeconds(): int
    {
        return $this->pollingIntervalSeconds;
    }

    /**
     * Sets the Unleash polling interval in seconds
     * @param int $pollingIntervalSeconds
     * @return Config
     */
    public function setPollingIntervalSeconds(int $pollingIntervalSeconds): Config
    {
        $this->pollingIntervalSeconds = $pollingIntervalSeconds;
        return $this;
    }

    /**
     * Gets the Unleash metrics send interval in seconds
     * @return int
     */
    public function getMetricsIntervalSeconds(): int
    {
        return $this->metricsIntervalSeconds;
    }

    /**
     * Sets the Unleash metrics send interval in seconds
     * @param int $metricsIntervalSeconds
     * @return Config
     */
    public function setMetricsIntervalSeconds(int $metricsIntervalSeconds): Config
    {
        $this->metricsIntervalSeconds = $metricsIntervalSeconds;
        return $this;
    }
}
