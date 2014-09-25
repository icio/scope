<?php

namespace spec\icio\Scope;


use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ErrorReportingSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf("icio\\Scope\\ErrorReporting");
    }

    function it_should_error_with_negative_error_levels()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', array(-1));
    }

    function it_should_error_with_a_really_high_error_level()
    {
        $this->shouldThrow('InvalidArgumentException')->during('__construct', array((E_ALL | E_STRICT) + 1));
    }

    function it_should_default_to_strictest_error_level()
    {
        $this->beConstructedWith(null);
        $this->getErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);
    }

    function it_should_set_the_error_reporting_level()
    {
        $prev = error_reporting(E_ALL | E_STRICT);

        $this->beConstructedWith(E_NOTICE);

        $this->getCurrentErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);
        $this->enter();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_NOTICE);
        error_reporting(E_NOTICE | E_WARNING);
        $this->leave();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);
        $this->enter();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_NOTICE);
        $this->leave();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);

        error_reporting($prev);
    }

    function it_should_track_the_error_reporting_level()
    {
        $prev = error_reporting(E_ALL | E_STRICT);

        $this->beConstructedWith(E_NOTICE | E_WARNING, true);

        $this->getCurrentErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);
        $this->enter();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_NOTICE | E_WARNING);
        error_reporting(E_NOTICE);
        $this->leave();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);
        $this->enter();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_NOTICE);
        $this->leave();
        $this->getCurrentErrorReportingLevel()->shouldReturn(E_ALL | E_STRICT);

        error_reporting($prev);
    }
} 
