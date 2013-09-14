Profiling
==========

To use xhprof profiler, please ensure that you've ran `scripts/install` and 
installed these dependencies.

Then change directory to vendor/xhprof/extension/ directoy, compile and install
the extension:

    phpize
    ./configure
    make
    make install

If you have phpbrew, run enable command to enable xhprof:

    phpbrew enable xhprof

Otherwise you have to add extension config to your ini file:

    extension=xhprof.so

Then you need to add XHProfPlugin to the config file 
`framework.yml`:

    Plugins:
      XHProfPlugin:

After this, change the rewrite rule in .htaccess file, you should set
environment variable `PHIFTY_XHPROF` by adding below statement to the virtual
host configuration.

Apache Rewrite Module can not read environment from `SetEnv` command, so we 
need to specify environment with RewriteRule command:

    SetEnv PHIFTY_XHPROF on   # don't work
    RewriteRule .* - [E=PHIFTY_XHPROF:on]    # work

The built-in `.htaccess` file has a rewrite rule checking `PHIFTY_XHPROF` and
redirect to `run_xhprof.php` script for wrapping with xhprof functions.

You can also simply use the below configuration in your virtual host config
for Apache:

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-s
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ run_xhprof.php/$1 [NC,L]

To profile on specific URL, you can also append `XHPROF` string to the query string,
eg:

    http://phifty.dev/bs/user?XHPROF

This enables XHProf extension.

