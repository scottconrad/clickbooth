<?php

namespace clickbooth_spec\Clickbooth;

use Clickbooth\Driver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VehicleSpec extends ObjectBehavior
{

    function let($let){
        $driver = new Driver();
        $driver->setName("Scott");
        $this->driver = $driver;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Clickbooth\Vehicle');
    }

    function it_should_accept_a_driver(\Clickbooth\Driver $driver){
        $this->driver = $driver;
    }

    function it_should_return_the_drivers_name(){
        return $this->driver->getName();
    }

    function it_should_be_able_to_be_cleaned(){
        $this->clean();
    }
}
