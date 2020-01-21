<?php

namespace spec\Minds\UnleashClient\Helpers;

use Minds\UnleashClient\Helpers\NormalizedValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NormalizedValueSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(NormalizedValue::class);
    }

    public function it_should_build()
    {
        $this
            ->build('', 'test', 100)
            ->shouldReturn(0);

        $this
            ->build('phpspec', 'test', 100)
            ->shouldReturn(96);

        $this
            ->build('minds', 'test', 100)
            ->shouldReturn(89);
    }
}
