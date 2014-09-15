<?php

use icio\Scope\KeyState;
use icio\Scope\With;
use icio\Scope\WorkingDirectory;

require_once './vendor/autoload.php';

var_dump($_GET, glob("*"));

With::scope(
    new KeyState($_GET, array('q' => 'test')),
    new WorkingDirectory("src/icio/AbstractScope"),
    function() {
        var_dump($_GET, glob("*"));
    }
);

var_dump($_GET, glob("*"));
