<?php
/**
 * GradualRolloutSessionIdStrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Helpers\NormalizedValue;
use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;

class GradualRolloutSessionIdStrategyAlgorithm implements StrategyAlgorithm
{
    /** @var LoggerInterface|Logger */
    protected $logger;

    /** @var NormalizedValue */
    protected $normalizedValue;

    /**
     * @inheritDoc
     * @param NormalizedValue $normalizedValue
     */
    public function __construct(
        LoggerInterface $logger = null,
        NormalizedValue $normalizedValue = null
    ) {
        $this->logger = $logger ?: new Logger();
        $this->normalizedValue = $normalizedValue ?: new NormalizedValue();
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(Strategy $strategy, Context $context): bool
    {
        $parameters = $strategy->getParameters();

        $percentage = intval($parameters['percentage'] ?? 0);
        $groupId = trim($parameters['groupId'] ?? '');
        $sessionId = trim($context->getSessionId() ?? '');

        if (!$sessionId) {
            return false;
        }

        $sessionIdValue = $this->normalizedValue
            ->build($sessionId, $groupId);

        $this->logger->debug(static::class, [
            $sessionIdValue,
            $percentage
        ]);

        return $percentage > 0 &&
            $sessionIdValue <= $percentage;
    }
}
