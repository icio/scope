<?php

namespace icio\Scope;

interface ScopeInterface
{
    public function enter();
    public function leave();
}
