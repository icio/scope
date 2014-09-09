<?php

namespace icio\Scope;

class With
{
    public static function scope()
    {
        $scopes = func_get_args();
        $callable = array_pop($scopes);
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException("The last argument should be callable.");
        }

        /** @var $scopes ScopeInterface[] */
        /** @var $enteredScopes ScopeInterface[] */

        $i = 0;
        try {
            foreach ($scopes as $scope) {
                $scope->enter();
                $i++;
            }
            $return = call_user_func_array($callable, $scopes);
        } catch (\Exception $e) {
            // Oh for finally..
            while ($i--) {
                $scopes[$i]->leave();
            }
            throw $e;
        }

        while ($i--) {
            $scopes[$i]->leave();
        }
        return $return;
    }
}
