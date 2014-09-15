<?php

namespace icio\Scope;

class With
{
    /**
     * @internal param $ScopeInterface ... $scope
     * @internal param callable $closure
     * @deprecated
     * @return mixed
     */
    public static function scope()
    {
        $scopes = func_get_args();
        $callable = array_pop($scopes);
        $scope = new Scope($scopes);
        return $scope->call($callable);
    }
}
