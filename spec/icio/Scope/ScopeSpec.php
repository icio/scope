<?php

namespace spec\icio\Scope;

use icio\Scope\ScopeInterface;
use PhpSpec\ObjectBehavior;

class ScopeSpec extends ObjectBehavior
{
    function let(ScopeInterface $a, ScopeInterface $b)
    {
        $this->beAnInstanceOf('icio\Scope\Scope');
        $this->beConstructedWith($a, $b);
    }

    function it_takes_a_list_of_scopes(ScopeInterface $a, ScopeInterface $b)
    {
        $this->beConstructedWith(array($a, $b));
        $this->getScopes()->shouldReturn(array($a, $b));
    }

    function it_takes_multiple_argments(ScopeInterface $a, ScopeInterface $b)
    {
        $this->getScopes()->shouldReturn(array($a, $b));
    }

    function it_calls_all_the_things(ScopeInterface $a, ScopeInterface $b, ClosureInterface $c)
    {
        $c->call($a, $b)->shouldBeCalled();

        $a->enter()->shouldBeCalled();
        $a->leave()->shouldBeCalled();
        $b->enter()->shouldBeCalled();
        $b->leave()->shouldBeCalled();

        $this->call(array($c, 'call'));
    }

    function it_leaves_despite_errors(ScopeInterface $a, ScopeInterface $b, ClosureInterface $c)
    {
        $c->call($a, $b)->willThrow(new SpecTestException);

        $a->enter()->shouldBeCalled();
        $a->leave()->shouldBeCalled();
        $b->enter()->shouldBeCalled();
        $b->leave()->shouldBeCalled();

        $this->shouldThrow('spec\icio\Scope\SpecTestException')->duringCall(array($c, 'call'));
    }

    function it_cleans_up_scope_enter_errors(ScopeInterface $a, ScopeInterface $b, ClosureInterface $c)
    {
        $c->call($a, $b)->shouldNotBeCalled();

        $a->enter()->shouldBeCalled();
        $a->leave()->shouldBeCalled();
        $b->enter()->willThrow(new SpecTestException);
        $b->leave()->shouldNotBeCalled();

        $this->shouldThrow('spec\icio\Scope\SpecTestException')->duringCall(array($c, 'call'));
    }
}

class SpecTestException extends \Exception
{
}

interface ClosureInterface
{
    public function call();
}
