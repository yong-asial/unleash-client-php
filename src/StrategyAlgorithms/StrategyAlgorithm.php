<?php
/**
 * StrategyAlgorithm
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\StrategyAlgorithms;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Entities\Strategy;
use Psr\Log\LoggerInterface;

interface StrategyAlgorithm
{
    /**
     * StrategyAlgorithm constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger);

    /**
     * Resolves a strategy using the context
     * @param Strategy $strategy
     * @param Context $context
     * @return bool
     */
    public function isEnabled(Strategy $strategy, Context $context): bool;
}
