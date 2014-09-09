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

    function it_calls_all_the_things(ScopeInterface $a, ScopeInterface $b, ClosureInterface $c)
    {
        $c->call($a, $b)->shouldBeCalled();

        $a->enter()->shouldBeCalled();
        $a->leave()->shouldBeCalled();
        $b->enter()->shouldBeCalled();
        $b->leave()->shouldBeCalled();

        $this->scope($a, $b, array($c, 'call'));
    }

    function it_leaves_despite_errors(ScopeInterface $a, ScopeInterface $b, ClosureInterface $c)
    {
        $c->call($a, $b)->willThrow(new SpecTestException);

        $a->enter()->shouldBeCalled();
        $a->leave()->shouldBeCalled();
        $b->enter()->shouldBeCalled();
        $b->leave()->shouldBeCalled();

        $this->shouldThrow('spec\icio\Scope\SpecTestException')->duringScope($a, $b, array($c, 'call'));
    }

    function it_cleans_up_scope_enter_errors(ScopeInterface $a, ScopeInterface $b, ClosureInterface $c)
    {
        $c->call($a, $b)->shouldNotBeCalled();

        $a->enter()->shouldBeCalled();
        $a->leave()->shouldBeCalled();
        $b->enter()->willThrow(new SpecTestException);
        $b->leave()->shouldNotBeCalled();

        $this->shouldThrow('spec\icio\Scope\SpecTestException')->duringScope($a, $b, array($c, 'call'));
    }
}

class SpecTestException extends \Exception
{
}

interface ClosureInterface
{
    public function call();
}
