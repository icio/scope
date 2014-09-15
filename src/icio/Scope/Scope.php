<?php

namespace icio\Scope;


class Scope extends AbstractScope
{
    /**
     * @var ScopeInterface[]
     */
    protected $scopes;

    /**
     * @var int
     */
    protected $enteredScopes = 0;

    /**
     * @param $scopes
     */
    public function __construct($scopes)
    {
        if (is_array($scopes)) {
            $this->scopes = $scopes;
        } else {
            $this->scopes = func_get_args();
        }
    }

    public function enterOnce()
    {
        foreach ($this->scopes as $scope) {
            $scope->enter();
            $this->enteredScopes++;
        }
    }

    public function leaveOnce()
    {
        while ($this->enteredScopes--) {
            $this->scopes[$this->enteredScopes]->leave();
        }
    }

    protected function getCallArguments()
    {
        return $this->getScopes();
    }

    /**
     * @return ScopeInterface[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }
}
