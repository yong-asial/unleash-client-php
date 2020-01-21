<?php
/**
 * StrategyAlgorithmFactory
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Logger;
use Minds\UnleashClient\StrategyAlgorithms\StrategyAlgorithm;
use Psr\Log\LoggerInterface;

class StrategyAlgorithmFactory
{
    /** @var LoggerInterface|Logger */
    protected $logger;

    /**
     * StrategyAlgorithmFactory constructor.
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?: new Logger();
    }

    /**
     * Builds a new strategy algorithm instance
     * @param Strategy $strategy
     * @return StrategyAlgorithm|null
     */
    public function build(Strategy $strategy): ?StrategyAlgorithm
    {
        $className = sprintf("\\Minds\\UnleashClient\\StrategyAlgorithms\\%sStrategyAlgorithm", ucfirst($strategy->getName()));

        if (!class_exists($className)) {
            $this->logger->warning("{$className} does not exist");
            return null;
        }

        $strategyAlgorithm = new $className($this->logger);

        if (!($strategyAlgorithm instanceof StrategyAlgorithm)) {
            $this->logger->warning(sprintf("%s is not an %s instance", $strategyAlgorithm, StrategyAlgorithm::class));
            return null;
        }

        $this->logger->debug("Created a new {$className} instance");

        return $strategyAlgorithm;
    }
}
