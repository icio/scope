# Scope

Scope offers a mechanism for preparing global environments and containers for the duration of a callback, keeping state mutation and clean-up to only declaration in your code. Inspired by Python's native [with]().

For example, instead of doing:

```php
global $x;
$xBackup = $x;
$x = $xNew;
$done = do_it();
$x = $xBackup;
```

with `With::scope` you can set an Array key state:

```php
(new KeyState($GLOBALS, array("x" => $newX)))->call('do_it');
```

Currently available scopes help with:

* Setting temporary values on arrays, with `KeyState`;
* Capturing function output, with `Buffer`; and
* Changing into a new directory, with `WorkingDirectory`.

All entered scopes are passed into the callback as function arguments. For example, when working with output buffers you can capture and return the output as so:

```php
$output = (new Scope(
	new Buffer(Buffer:CLEAN),
    new WorkingDirectory("./old")
))->call(function(Buffer $buffer) {
	require "old-script.php";
    return $buffer->getContents();
});
```

If exceptions are thrown during the callback then we close the scopes and re-throw the exception. If exceptions are thrown by the scopes then we clean up any already entered and re-throw the exception. Exceptions thrown whilst trying to leave a scope prevent the clean-up of others (which should perhaps change).

The `Buffer` can collect the output across multiple sessions, the `WorkingDirectory` can go back to the directory it left upon re-entry, and `KetState` can track the value of keys, should they be changed in-scope. See the spec tests for details.