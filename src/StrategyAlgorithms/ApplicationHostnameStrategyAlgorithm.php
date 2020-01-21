<?php
/**
 * ApplicationHostnameStrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;

class ApplicationHostnameStrategyAlgorithm implements StrategyAlgorithm
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
        $hostNamesList = $parameters['hostNames'] ?? '';

        if (!$hostNamesList) {
            return false;
        }

        $hostNames = array_map([$this, 'normalizeHostName'], explode(',', $hostNamesList));

        $this->logger->debug(static::class, [
            $context->getHostName(),
            $hostNames
        ]);

        return in_array(strtolower($context->getHostName()), $hostNames, true);
    }

    /**
     * Normalizes host names for lookup
     * @param string $hostName
     * @return string
     */
    protected function normalizeHostName(string $hostName): string
    {
        return trim(strtolower($hostName));
    }
}
