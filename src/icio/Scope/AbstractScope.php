<?php

namespace icio\Scope;

abstract class AbstractScope implements ScopeInterface
{
    private $in = false;

    public function call($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('AbstractScope::call expects a callable for $callback');
        }
        try {
            $this->enter();
            $return = call_user_func_array($callback, $this->getCallArguments());
        } catch (\Exception $e) {
            $this->leave();
            throw $e;
        }
        $this->leave();
        return $return;
    }

    protected function getCallArguments()
    {
        return array($this);
    }

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
