<?php
/**
 * RemoteAddressStrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;

class RemoteAddressStrategyAlgorithm implements StrategyAlgorithm
{
    /** @var LoggerInterface|Logger */
    protected $logger;

    /**
     * @inheritDoc
     */
    public function __construct(
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?: new Logger();
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(Strategy $strategy, Context $context): bool
    {
        $parameters = $strategy->getParameters() ?? [];
        $ipAddressesList = $parameters['IPs'] ?? '';

        if (!$ipAddressesList) {
            return false;
        }

        $ipAddresses = array_map([$this, 'normalizeIpAddress'], explode(',', $ipAddressesList));

        $this->logger->debug(static::class, [
            $context->getRemoteAddress(),
            $ipAddresses
        ]);

        return in_array(strtolower($context->getRemoteAddress()), $ipAddresses, true);
    }

    /**
     * Normalizes IP addresses for lookup, using lowercase for IPv6
     * @param string $ipAddress
     * @return string
     */
    protected function normalizeIpAddress(string $ipAddress): string
    {
        return trim(strtolower($ipAddress));
    }
}
