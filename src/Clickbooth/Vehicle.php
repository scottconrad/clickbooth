<?php

namespace Clickbooth;

class Vehicle
{

    public $driver;

    public function clean()
    {
        // TODO: write logic here
    }

    public function getName(){
        if($this->driver) return $this->driver->getName();
    }
}
