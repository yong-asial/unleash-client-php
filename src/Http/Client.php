<?php

namespace Minds\UnleashClient\Http;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Minds\UnleashClient\Logger;
use Minds\UnleashClient\Version;
use Psr\Log\LoggerInterface;

/**
 * Wraps a Guzzle HTTP client in Unleash specific functions
 * @package Minds\UnleashClient\Http
 */
class Client
{
    /** @var HttpClient */
    protected $httpClient;

    /** @var Config */
    protected $config;

    /** @var LoggerInterface */
    protected $logger;

    /** @var string */
    protected $id;

    /**
     * Client constructor.
     * @param Config $config
     * @param LoggerInterface|null $logger
     * @param HttpClient|null $httpClient
     */
    public function __construct(
        Config $config,
        LoggerInterface $logger = null,
        HttpClient $httpClient = null
    ) {
        $this->config = $config;
        $this->logger = $logger ?: new Logger();
        $this->httpClient = $httpClient ?: $this->createHttpClient();

        $this->logger->debug("Client configured. Base URL: {$this->config->getApiUrl()}");
    }

    /**
     * Gets the client ID based on configuration
     * @return string
     */
    public function getId(): string
    {
        if (!$this->id) {
            $this->regenerateId();
        }

        return $this->id;
    }

    /**
     * Calls the Unleash api and registers the client
     * @param string|null $date
     * @return bool
     */
    public function register(string $date = null): bool
    {
        $date = $date ?? date("c");

        $this->logger->debug('Registering client');
        try {
            $payload = [
                'appName' => $this->config->getApplicationName(),
                'instanceId' => $this->config->getInstanceId(),
                'sdkVersion' => sprintf("unleash-client-php:%s", Version::get()),
                'strategies' => [],
                'started' => $date,
                'interval' => $this->config->getMetricsIntervalSeconds() * 1000
            ];

            $this->logger->debug('Client payload', $payload);

            $this->regenerateId();

            $response = $this->httpClient->request('POST', 'client/register', [
                'json' => $payload,
            ]);

            return
                $response->getStatusCode() >= 200 &&
                $response->getStatusCode() < 300;
        } catch (Exception $e) {
            $this->logger->error($e);
            return false;
        }
    }

    /**
     * Calls the unleash api for getting feature flags.
     * If HTTP 2xx, reconstitutes the feature flags and returns the decoded JSON response.
     * Else, logs and error and returns an empty array.
     * @return array
     */
    public function fetch(): array
    {
        $this->logger->debug('Getting feature flags');

        try {
            $response = $this->httpClient->get('client/features');
            $this->logger->debug("Got feature flags [{$response->getStatusCode()}]");

            if (
                $response->getStatusCode() >= 200 &&
                $response->getStatusCode() < 300
            ) {
                $body = json_decode((string) $response->getBody(), true) ?? [];
                return $body['features'] ?? [];
            }
        } catch (Exception $e) {
            $this->logger->error($e);
        }

        return [];
    }

    /**
     * Creates an http client with the auth headers
     * and middleware
     */
    protected function createHttpClient(): HttpClient
    {
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                $this->logger,
                new MessageFormatter('{uri} -> {req_body} => {res_body}')
            )
        );

        return new HttpClient([
            'base_uri' => $this->config->getApiUrl(),
            'headers' => [
                "UNLEASH-APPNAME" => $this->config->getApplicationName(),
                "UNLEASH-INSTANCEID" => $this->config->getInstanceId(),
            ],
            'handler' => $stack
        ]);
    }

    /**
     * Generates an ID based on the config values
     */
    protected function regenerateId(): void
    {
        $this->id = substr(sha1(implode(':', [
            $this->config->getApiUrl(),
            $this->config->getApplicationName(),
            $this->config->getInstanceId(),
            Version::get()
        ])), 4, 9);
    }
}
