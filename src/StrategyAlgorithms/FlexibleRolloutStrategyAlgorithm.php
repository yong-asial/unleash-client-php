<?php
/**
 * FlexibleRolloutStrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Helpers\NormalizedValue;
use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;

class FlexibleRolloutStrategyAlgorithm implements StrategyAlgorithm
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

        $percentage = intval($parameters['rollout'] ?? 0);
        $stickiness = $parameters['stickiness'] ?? 'default';
        $groupId = trim($parameters['groupId'] ?? '');
        $userId = trim($context->getUserId() ?? '');
        $sessionId = trim($context->getSessionId() ?? '');
        $randomId = sprintf("%s", $this->normalizedValue->random(999999, 1));

        switch ($stickiness) {
            case 'userId':
                $stickinessId = $userId;
                break;

            case 'sessionId':
                $stickinessId = $sessionId;
                break;

            case 'random':
                $stickinessId = $randomId;
                break;

            default:
                $stickinessId = $userId ?: $sessionId ?: $randomId;
                break;
        }

        if (!$stickinessId) {
            return false;
        }

        $stickinessValue = $this->normalizedValue
            ->build($stickinessId, $groupId);

        $this->logger->debug(static::class, [
            $stickiness,
            $stickinessValue,
            $percentage
        ]);

        return $percentage > 0 &&
            $stickinessValue <= $percentage;
    }
}
