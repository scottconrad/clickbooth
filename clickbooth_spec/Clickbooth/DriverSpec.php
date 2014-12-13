<?php

namespace clickbooth_spec\Clickbooth;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DriverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Clickbooth\Driver');
    }
}
