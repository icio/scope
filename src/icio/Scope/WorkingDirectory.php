<?php

namespace icio\Scope;

class WorkingDirectory extends Scope
{
    protected $previousDirectory;
    protected $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function enterOnce()
    {
        $this->previousDirectory = getcwd();
        if (!$this->changeDirectory($this->directory)) {
            throw new \RuntimeException("Unable to enter working directory");
        }
    }

    public function leaveOnce()
    {
        chdir($this->previousDirectory);
    }

    protected function changeDirectory($dir)
    {
        return chdir($dir);
    }
}
