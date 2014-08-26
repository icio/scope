<?php

namespace icio\Scope;

class KeyState extends Scope
{
    protected $target;
    protected $state;

    protected $added = array();
    protected $modified = array();

    public function __construct(&$target, array $state)
    {
        $this->target = &$target;
        $this->state = $state;
    }

    public function enterOnce()
    {
        $this->added = array();
        $this->modified = array();

        foreach ($this->state as $prop => $value) {
            if (isset($this->target[$prop])) {
                $this->modified[$prop] = $this->target[$prop];
            } else {
                $this->added[] = $prop;
            }
            $this->target[$prop] = $value;
        }
    }

    public function leaveOnce()
    {
        foreach ($this->modified as $prop => $value) {
            $this->target[$prop] = $value;
        }
        foreach ($this->added as $prop) {
            unset($this->target[$prop]);
        }
    }

    public function getAddedGlobals()
    {
        return $this->added;
    }
    public function getModifiedGlobals()
    {
        return $this->modified;
    }
}
