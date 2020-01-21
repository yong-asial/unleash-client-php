<?php
/**
 * UserWithIdStrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Logger;
use Psr\Log\LoggerInterface;

class UserWithIdStrategyAlgorithm implements StrategyAlgorithm
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
        $userIdsList = $parameters['userIds'] ?? '';

        if (!$userIdsList) {
            return false;
        }

        $userIds = array_map([$this, 'normalizeUserId'], explode(',', $userIdsList));

        $prefixedUserGroups = array_map([$this, 'prefixedUserGroup'], $context->getUserGroups() ?: []);

        $this->logger->debug(static::class, [
            $context->getUserId(),
            $prefixedUserGroups,
            $userIds
        ]);

        $isAtLeastAGroupOnArray = $prefixedUserGroups &&
            count(array_intersect($prefixedUserGroups, $userIds)) > 0;

        $isUserOnArray = $context->getUserId() &&
            in_array($context->getUserId(), $userIds, true);

        return $isAtLeastAGroupOnArray || $isUserOnArray;
    }

    /**
     * Normalizes user IDs for lookup
     * @param string $userId
     * @return string
     */
    protected function normalizeUserId(string $userId): string
    {
        return trim((string) $userId);
    }

    /**
     * Adds the group prefix to user groups for lookup
     * @param string $userGroup
     * @return string
     */
    protected function prefixedUserGroup(string $userGroup): string
    {
        return "%{$userGroup}";
    }
}
