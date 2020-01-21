<?php

namespace spec\Minds\UnleashClient\Entities;

use Minds\UnleashClient\Entities\Strategy;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StrategySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Strategy::class);
    }

    public function it_should_set_and_get_name()
    {
        $this
            ->getName()
            ->shouldReturn('');

        $this
            ->setName('phpspec')
            ->getName()
            ->shouldReturn('phpspec');
    }

    public function it_should_set_and_get_parameters()
    {
        $this
            ->getParameters()
            ->shouldReturn([]);

        $this
            ->setParameters([
                'phpspec' => 1
            ])
            ->getParameters()
            ->shouldReturn([
                'phpspec' => 1
            ]);
    }
}
