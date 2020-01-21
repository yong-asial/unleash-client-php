<?php

namespace spec\Minds\UnleashClient\Entities;

use Minds\UnleashClient\Entities\Context;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContextSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Context::class);
    }

    public function it_should_set_and_get_user_id()
    {
        $this
            ->getUserId()
            ->shouldReturn(null);

        $this
            ->setUserId('phpspec')
            ->getUserId()
            ->shouldReturn('phpspec');
    }

    public function it_should_set_and_get_session_id()
    {
        $this
            ->getSessionId()
            ->shouldReturn(null);

        $this
            ->setSessionId('phpspec')
            ->getSessionId()
            ->shouldReturn('phpspec');
    }

    public function it_should_set_and_get_remote_address()
    {
        $this
            ->getRemoteAddress()
            ->shouldReturn(null);

        $this
            ->setRemoteAddress('127.0.0.1')
            ->getRemoteAddress()
            ->shouldReturn('127.0.0.1');
    }

    public function it_should_set_and_get_host_name()
    {
        $this
            ->getHostName()
            ->shouldReturn(null);

        $this
            ->setHostName('phpspec.test')
            ->getHostName()
            ->shouldReturn('phpspec.test');
    }
}
