<?php
/**
 * StrategyResolver
 *
 * @author edgebal
 */

namespace Minds\UnleashClient;

use Minds\UnleashClient\Entities\Context;
use Minds\UnleashClient\Factories\StrategyAlgorithmFactory;
use Psr\Log\LoggerInterface;

/**
 * Build and resolve feature strategies
 * @package Minds\UnleashClient
 */
class StrategyResolver
{
    /** @var LoggerInterface|Logger */
    protected $logger;

    /** @var StrategyAlgorithmFactory */
    protected $strategyAlgorithmFactory;

    /**
     * StrategyResolver constructor.
     * @param LoggerInterface|null $logger
     * @param StrategyAlgorithmFactory|null $strategyAlgorithmFactory
     */
    public function __construct(
        LoggerInterface $logger = null,
        StrategyAlgorithmFactory $strategyAlgorithmFactory = null
    ) {
        $this->logger = $logger ?: new Logger();
        $this->strategyAlgorithmFactory = $strategyAlgorithmFactory ?: new StrategyAlgorithmFactory($this->logger);
    }

    /**
     * Instantiates a new strategy algorithm based on the passed strategy and run it against the context
     * @param array $strategies
     * @param Context $context
     * @return bool
     */
    public function isEnabled(array $strategies, Context $context): bool
    {
        foreach ($strategies as $strategy) {
            $strategyAlgorithm = $this->strategyAlgorithmFactory
                ->build($strategy);

            if ($strategyAlgorithm->isEnabled($strategy, $context)) {
                return true;
            }
        }

        return false;
    }
}
