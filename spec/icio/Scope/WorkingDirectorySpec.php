<?php

namespace spec\icio\Scope;


use PhpSpec\ObjectBehavior;

class WorkingDirectorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('icio\Scope\WorkingDirectory');
    }

    function it_should_error_if_unable_to_cd()
    {
        $this->beConstructedWith("some-nonexistant-directory");
        $this->shouldThrow("RuntimeException")->duringEnter();
    }

    function it_should_cd()
    {
        $initial = getcwd();
        $parent = realpath(dirname(__DIR__.'/../'));

        $this->beConstructedWith($parent);

        $this->enter();
        $this->getCurrentWorkingDirectory()->shouldReturn($parent);
        $this->leave();
        $this->getCurrentWorkingDirectory()->shouldReturn($initial);
    }

    function it_should_track_other_cds()
    {
        $initial = getcwd();
        $parent = realpath(dirname(__DIR__.'/../'));
        $other = realpath(dirname(__DIR__.'/../../'));

        $this->beConstructedWith($other, true);

        $this->enter();
        $this->getCurrentWorkingDirectory()->shouldReturn($other);
        $this->leave();

        $this->getCurrentWorkingDirectory()->shouldReturn($initial);

        $this->enter();
        $this->getCurrentWorkingDirectory()->shouldReturn($other);
        chdir($parent);
        $this->leave();

        $this->getCurrentWorkingDirectory()->shouldReturn($initial);

        $this->enter();
        $this->getCurrentWorkingDirectory()->shouldReturn($parent);
        $this->leave();
        $this->getCurrentWorkingDirectory()->shouldReturn($initial);
    }
} 
