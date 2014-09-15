<?php

namespace spec\icio\Scope;


use icio\Scope\Buffer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BufferSpec extends ObjectBehavior
{
    function let($behaviour)
    {
        $this->beAnInstanceOf('icio\Scope\Buffer');
    }

    function it_should_take_the_behaviour($behaviour)
    {
        $this->beConstructedWith($behaviour);
        $this->getExitBehaviour()->shouldReturn($behaviour);
    }

    function it_should_capture_callback_output()
    {
        $this->beConstructedWith(Buffer::CLEAN);
        $this->call(
            function (Buffer $buffer) {
                echo "test";
                return $buffer->getContents();
            }
        )->shouldReturn("test");
    }

    function it_should_clean_output()
    {
        $this->beConstructedWith(Buffer::CLEAN);
        $trapBuffer = new Buffer(Buffer::CLEAN);

        $self = $this;
        $this->call(
            function () use ($self, $trapBuffer) {
                $trapBuffer->call(
                    function () {
                        echo "This should be trapped";
                    }
                );
                $self->getContents()->shouldReturn("");
            }
        );
    }

    function it_should_flush_output()
    {
        $this->beConstructedWith(Buffer::CLEAN);
        $flushBuffer = new Buffer(Buffer::FLUSH);

        $self = $this;
        $this->call(
            function () use ($self, $flushBuffer) {
                $flushBuffer->call(
                    function () {
                        echo "This should be flushed";
                    }
                );
                $self->getContents()->shouldReturn("This should be flushed");
            }
        );
    }

    function it_should_store_output()
    {
        $this->beConstructedWith(Buffer::STORE);

        $this->call(
            function () {
                echo "Hello, ";
            }
        );
        $this->call(
            function () {
                echo "world.";
            }
        );

        $this->getContents()->shouldReturn("Hello, world.");
    }
} 
