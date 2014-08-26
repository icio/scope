<?php

namespace icio\Scope;

class Buffer extends Scope
{
    const CLEAN = 1;
    const FLUSH = 2;

    protected $exitBehaviour;

    public function __construct($exitBehaviour = self::FLUSH)
    {
        $this->setExitBehaviour($exitBehaviour);
    }

    public function enterOnce()
    {
        $this->startBuffer();
    }
    public function leaveOnce()
    {
        if ($this->exitBehaviour == self::CLEAN) {
            $this->cleanAndEndBuffer();
        } elseif ($this->exitBehaviour == self::FLUSH) {
            $this->flushAndEndBuffer();
        }
    }

    protected function startBuffer()
    {
        ob_start();
    }
    protected function cleanAndEndBuffer()
    {
        ob_end_clean();
    }
    protected function flushAndEndBuffer()
    {
        ob_end_flush();
    }

    public function getExitBehaviour()
    {
        return $this->exitBehaviour;
    }
    public function setExitBehaviour($behaviour)
    {
        $this->exitBehaviour = $behaviour;
    }

    public function getContents()
    {
        return ob_get_contents();
    }
}
