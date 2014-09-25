<?php

namespace icio\Scope;


class ErrorReporting extends AbstractScope
{
    protected $errorLevel;
    protected $trackErrorLevel;
    protected $previousErrorLevel;

    const E_FULL = 32767; // E_ALL | E_STRICT

    public function __construct($errorLevel = null, $trackErrorLevel = false)
    {
        if ($errorLevel === null) {
            $this->errorLevel = self::E_FULL;
        } elseif ($errorLevel >= 0 && $errorLevel <= self::E_FULL) {
            $this->errorLevel = $errorLevel;
        } else {
            throw new \InvalidArgumentException(
                "errorLevel should be in range 0 to " . self::E_FULL . ". See E_* constants."
            );
        }
        $this->trackErrorLevel = $trackErrorLevel;
    }

    public function enterOnce()
    {
        $this->previousErrorLevel = error_reporting($this->errorLevel);
    }

    public function leaveOnce()
    {
        $leftErrorLevel = error_reporting($this->previousErrorLevel);
        if ($this->trackErrorLevel) {
            $this->errorLevel = $leftErrorLevel;
        }
    }

    public function getErrorReportingLevel()
    {
        return $this->errorLevel;
    }

    public function getCurrentErrorReportingLevel()
    {
        return error_reporting();
    }
}
