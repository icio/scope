<?php

namespace spec\icio\Scope;

use icio\Scope\KeyState;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KeyStateSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('icio\Scope\KeyState');
    }

    function it_should_add_and_modify_keys(\ArrayObject $data)
    {
        $data = new \ArrayObject(array('ignored' => 0, 'changed' => 0));
        $this->beConstructedWith($data, array('changed' => 1, 'added' => 1));

        $this->enter();

        // The array should be in our modified state now
        $this->getAddedKeys()->shouldReturn(array("added" => true));
        $this->getModifiedKeys()->shouldReturn(array("changed" => 0));
        $this->getTarget()->getArrayCopy()->shouldReturn(array('ignored' => 0, 'changed' => 1, 'added' => 1));

        $this->leave();

        // The array should be back to normal
        $this->getTarget()->getArrayCopy()->shouldReturn(array('ignored' => 0, 'changed' => 0));
    }

    function it_should_track_changing_keys()
    {
        $data = new \ArrayObject();
        $this->beConstructedWith($data, array('q' => '/home/'), array('q'));

        $this->getState()->shouldReturn(array('q' => '/home/'));
        $this->getTrackedKeys()->shouldReturn(array('q'));

        // Mutate the variable we're tracking
        $this->enter();
        $data['q'] = '/home';
        $this->leave();
        $this->getState()->shouldReturn(array('q' => '/home'));
        $this->getTarget()->getArrayCopy()->shouldReturn(array());

        // Check it's how we left it
        $this->enter();
        $this->getTarget()->getArrayCopy()->shouldReturn(array('q' => '/home'));
        $this->leave();
    }
} 
