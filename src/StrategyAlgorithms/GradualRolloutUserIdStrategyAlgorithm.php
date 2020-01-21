<?php
/**
 * GradualRolloutUserIdStrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Helpers\NormalizedValue;
use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;

class GradualRolloutUserIdStrategyAlgorithm implements StrategyAlgorithm
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
        $userId = trim($context->getUserId() ?? '');

        if (!$userId) {
            return false;
        }

        $userIdValue = $this->normalizedValue
            ->build($userId, $groupId);

        $this->logger->debug(static::class, [
            $userIdValue,
            $percentage
        ]);

        return $percentage > 0 &&
            $userIdValue <= $percentage;
    }
}
