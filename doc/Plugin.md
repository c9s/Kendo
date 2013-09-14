Plugin
======


Plugin API
----------

Please see `src/Phifty/Plugin.php` and `src/Phifty/PluginManager.php` class files.

Plugin is registered to phifty kernel through PluginService class, to get the plugin manager object, 
you can use the accessor `plugins`:

    kernel()->plugins

To iterate through plugins:

    foreach( kernel()->plugins as $name => $plugin ) {
        // ...
    }

To check if a plugin is loaded:

    kernel()->plugins->has('Mailer');

To get the plugin instance:

    kernel()->plugins->get('Mailer');

