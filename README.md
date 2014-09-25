# Scope [![Build Status](https://travis-ci.org/icio/scope.svg?branch=master)](https://travis-ci.org/icio/scope)

Scope offers a mechanism for preparing global environments and containers for the duration of a callback, keeping state mutation and clean-up to only declaration in your code. Inspired by Python's native [with](https://docs.python.org/2/reference/datamodel.html#with-statement-context-managers).

For example, instead of doing:

```php
global $x;
$xBackup = $x;
$x = $xNew;
$done = do_it();
$x = $xBackup;
```

with `KeyState` you can set an Array key state:

```php
(new KeyState($GLOBALS, array("x" => $newX)))->call('do_it');
```

Currently available scopes help with:

* Collecting multiple scopes together, with `Scope`;
* Setting temporary values on arrays, with `KeyState`;
* Capturing function output, with `Buffer`;
* Changing into a new directory, with `WorkingDirectory`; and
* Worrying about different error levels, with `ErrorReporting`.

Sometimes you'll want to hop in and out of a given scope, providing default context by following the changes incurred by your actions within. The scopes can optionally account for this. The `Buffer` can collect the output across multiple sessions; the `WorkingDirectory` can go back to the directory it left upon re-entry; the `KetState` can track the value of keys, should they be changed in-scope; and the `ErrorReporting` can track any changes to the reporting. See the spec tests for details.

All entered scopes are passed into the callback as function arguments. For example, when working with output buffers you can capture and return the output as so:

```php
$output = (new Scope(
    new Buffer(Buffer::CLEAN),
    new WorkingDirectory("./old")
))->call(function(Buffer $buffer) {
    require "old-script.php";
    return $buffer->getContents();
});
```

If exceptions are thrown during the callback then we close the scopes and re-throw the exception. If exceptions are thrown by the scopes then we clean up any already entered and re-throw the exception. Exceptions thrown whilst trying to leave a scope prevent the clean-up of others (which should perhaps change).
