<?php

namespace icio\Scope;

class KeyState extends AbstractScope
{
    /**
     * @var mixed
     */
    protected $target;

    /**
     * @var array
     */
    protected $state;

    /**
     * @var array
     */
    protected $trackedKeys;

    /**
     * @var array
     */
    protected $added = array();

    /**
     * @var array
     */
    protected $modified = array();

    /**
     * @param array $target       The array we wish to mutate
     * @param array $state        The values to set within the scope
     * @param array $trackedKeys  Keys whose values we should retain across scope calls
     */
    public function __construct(&$target, array $state, array $trackedKeys = array())
    {
        $this->target = &$target;
        $this->state = $state;
        $this->trackedKeys = $trackedKeys;
    }

    public function enterOnce()
    {
        foreach ($this->state as $prop => $value) {
            if (isset($this->target[$prop])) {
                $this->modified[$prop] = $this->target[$prop];
            } else {
                $this->added[$prop] = true;
            }
            $this->target[$prop] = $value;
        }
    }

    public function leaveOnce()
    {
        foreach ($this->trackedKeys as $key) {
            if (isset($this->target[$key])) {
                $this->state[$key] = $this->target[$key];
            } else {
                unset($this->state[$key]);
            }
        }

        // Revert the modified properties
        foreach ($this->modified as $prop => $value) {
            $this->target[$prop] = $value;
        }
        $this->modified = array();

        // Revert the added properties
        foreach ($this->added as $prop => $_) {
            unset($this->target[$prop]);
        }
        $this->added = array();
    }

    /**
     * @return array
     */
    public function getAddedKeys()
    {
        return $this->added;
    }

    /**
     * @return array
     */
    public function getModifiedKeys()
    {
        return $this->modified;
    }

    /**
     * mergeState will add additional key/value pairs to be set when entering
     * the scope. Note that this will only take effect the next time that the
     * scope is entered.
     *
     * @param $state
     */
    public function mergeState($state)
    {
        $this->state = array_merge($this->state, $state);
    }

    /**
     * @param array $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return array
     */
    public function getTrackedKeys()
    {
        return $this->trackedKeys;
    }

    /**
     * @param array $trackedKeys
     */
    public function setTrackedKeys($trackedKeys)
    {
        $this->trackedKeys = $trackedKeys;
    }
}
