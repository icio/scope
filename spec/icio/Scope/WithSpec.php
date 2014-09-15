<?php

namespace spec\icio\Scope;

use icio\Scope\ScopeInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WithSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('icio\Scope\With');
    }
}
