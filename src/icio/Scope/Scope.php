<?php

namespace icio\Scope;

abstract class Scope implements ScopeInterface
{
    private $in = false;

    public function enter()
    {
        if ($this->in) {
            throw new \RuntimeException("Cannot enter scope we're already in.");
        } else {
            $this->in = true;
            $this->enterOnce();
        }
    }

    public function leave()
    {
        if ($this->in) {
            $this->in = false;
            $this->leaveOnce();
        } else {
            throw new \RuntimeException("Cannot exit scope we're not in.");
        }
    }

    public function isIn()
    {
        return $this->in;
    }

    abstract public function enterOnce();
    abstract public function leaveOnce();
}
