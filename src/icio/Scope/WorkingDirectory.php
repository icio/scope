<?php

namespace icio\Scope;

class WorkingDirectory extends AbstractScope
{
    protected $previousDirectory;
    protected $directory;
    protected $trackChanges = false;

    /**
     * @param string $directory
     * @param bool   $trackChanges
     */
    public function __construct($directory, $trackChanges=false)
    {
        $this->directory = $directory;
        $this->trackChanges = $trackChanges;
    }

    public function enterOnce()
    {
        $this->previousDirectory = $this->getCurrentWorkingDirectory();
        $this->setCurrentWorkingDirectory($this->directory);
    }

    public function leaveOnce()
    {
        if ($this->trackChanges) {
            $this->directory = $this->getCurrentWorkingDirectory();
        }
        $this->setCurrentWorkingDirectory($this->previousDirectory);
    }

    public function setCurrentWorkingDirectory($dir)
    {
        if (!@chdir($dir)) {
            throw new \RuntimeException("Unable to change directory to $dir");
        }
    }

    public function getCurrentWorkingDirectory()
    {
        return getcwd();
    }
}
