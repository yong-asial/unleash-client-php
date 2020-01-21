<?php

namespace spec\Minds\UnleashClient\Entities;

use Minds\UnleashClient\Entities\Feature;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeatureSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Feature::class);
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

    public function it_should_set_and_get_description()
    {
        $this
            ->getDescription()
            ->shouldReturn('');

        $this
            ->setDescription('phpspec')
            ->getDescription()
            ->shouldReturn('phpspec');
    }

    public function it_should_set_and_get_enabled_flag()
    {
        $this
            ->isEnabled()
            ->shouldReturn(false);

        $this
            ->setEnabled(true)
            ->isEnabled()
            ->shouldReturn(true);

        $this
            ->setEnabled(false)
            ->isEnabled()
            ->shouldReturn(false);
    }
}
