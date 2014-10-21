Peridot Yo Plugin
=================

Notify the right people when tests pass or fail, via the world's simplest messaging service [Yo](http://www.justyo.co/)

This is a plugin for PHP's event driven testing framework [Peridot](http://peridot-php.github.io/)

##Usage

Using the plugin is easy via your [peridot.php](http://peridot-php.github.io/#plugins) file:

```php
<?php
use Evenement\EventEmitterInterface;
use Peridot\Plugin\Yo\YoPlugin;

return function (EventEmitterInterface $emitter) {
    /**
     * Register by passing the peridot event emitter,
     * a yo api token obtained from http://dev.justyo.co/,
     * an an array of users to be Yo'ed,
     * and an optional string or function that returns a link string
     */
    $plugin = YoPlugin::register($emitter, "your-yo-token", ['USER1', 'USER2'], 'http://linktobuild.com');
};
```

##Behaviors
This plugin has a few behaviors so you can configure when you want to receive Yo notifications. These all
exist as constants on the `YoPlugin` class, and can be passed to the plugin like so:

```php
<?php
use Evenement\EventEmitterInterface;
use Peridot\Plugin\Yo\YoPlugin;

return function (EventEmitterInterface $emitter) {
    $plugin = YoPlugin::register($emitter, "your-yo-token", ['USER1', 'USER2'], 'http://linktobuild.com');
    $plugin->setBehavior(YoPlugin::BEHAVIOR_ON_PASS);
};
```

###BEHAVIOR_ON_PASS

Receive Yo notifications when the test suite passes.

###BEHAVIOR_ON_FAIL

Receive Yo notifications when the test suite fails. This is the default behavior.

###BEHAVIOR_ALWAYS

Receive Yo notifications when the test suite finishes running.

##Running tests

Tests were written using [Peridot](http://peridot-php.github.io/). You can run them like so:

```
$ vendor/bin/peridot specs/
```

##Contributing

Pull requests, issues, and feedback are of course always welcome.
