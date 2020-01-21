<?php

namespace spec\Minds\UnleashClient\Factories;

use Minds\UnleashClient\Entities\Strategy;
use Minds\UnleashClient\Factories\StrategyFactory;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StrategyFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(StrategyFactory::class);
    }

    public function it_should_build()
    {
        $this
            ->build([
                'name' => 'phpspec',
                'parameters' => [
                    'test' => 1,
                ]
            ])
            ->shouldBeAStrategy([
                'name' => 'phpspec',
                'parameters' => [
                    'test' => 1,
                ]
            ]);
    }


    public function getMatchers(): array
    {
        return [
            'beAStrategy' => function ($subject, $data) {
                if (!($subject instanceof Strategy)) {
                    throw new FailureException(sprintf("Subject should be a %s instance", Strategy::class));
                }

                if ($subject->getName() !== $data['name']) {
                    throw new FailureException('Unexpected subject getName() value');
                }

                if ($subject->getParameters() !== $data['parameters']) {
                    throw new FailureException('Unexpected subject getParameters() value');
                }

                return true;
            }
        ];
    }
}
