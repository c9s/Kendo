Setup Environment
=================

## Operating System

### Debian System

    sudo apt-get install apache2 apache2-mpm-prefork php5
    sudo apt-get build-dep php5 php5-xdebug php5-apc php5-memcache
   
### Mac OS System

#### Macports

我們有提供 Macports Mirror 請從底下 clone 下來，在 svn branch 上。

    git clone --branch svn git@git.corneltek.com:mports.git

改 /opt/local/etc/macports/sources.conf 指定到你的 port mirror:

    file:///Users/c9s/mports/dports [default]

#### Package requirements

    sudo port install -Rucvp mysql5-server mysql5 postgresql92 postgresql92-server sqlite3
    sudo port install -Rucvp curl automake autoconf $(port echo depof:php5)

如果 postgresql 不行的話，就 downgrade 到 9.1, 9.0 版本，都可。

## HTTP Server

可選用 Apache 或用 Nginx 作為 HTTP Server

### Apache Requirement

    sudo a2enmod rewrite



## PHP 環境

PHP 5.4+ is optional, to run php5.4 or upper, you can choose phpbrew to
install the latest PHP on your machine.

如果是在 debian 上面的話，可以用 phpbrew 裝一下 php 5.4.0 版本
https://github.com/c9s/phpbrew

用 phpbrew 裝的 variant 大概如下:

    phpbrew install php-5.4.5 +default+dbs --apxs2=/opt/local/apache2/bin/apxs

    phpbrew install php-5.4.5 +default+dbs +apxs2=/opt/local/apache2/bin/apxs

    phpbrew install php-5.4.5 +default +mysql +sqlite +pgsql=/opt/local/lib/postgresql91  \
                +apxs2=/opt/local/apache2/bin/apxs

apxs2 是指定到 Apache 的 API 工具，用來 build php apache module, 
也會自動在設定檔安插 php 模組載入項目

如果 MacPorts 有裝 PostgreSQL 的話，Mac OS 也有內建，如果不指定 +pgsql，就是使用內建的。

請確定你現在運行的 Apache 所屬的 apxs，如果你使用 Macports 的 Apache，請確定不是 Apple 內建的 apxs

要確定你現在運行的 Apache, 可以執行:

    ps ax | grep -E 'httpd|apache'

若運行的 Apache 如:

    21310   ??  Ss     0:01.41 /opt/local/apache2/bin/httpd -k restart

那麼就是 Macports 的 Apache, 請選用 /opt/local/apache2/bin/apxs, 若是 Apple 內建的 Apache, 請執行:

    whereis apxs

或是

    locate apxs

來尋找能選用的 apxs 位於哪個位置。

如果有遇到問題的話，請到 GitHub 上面發個 issue。

## PHPUnit 測試工具

要裝一下 PHPUnit ，大概看一下 PEAR Installer 怎麼用跟怎麼寫。

<http://www.phpunit.de/manual/3.7/en/index.html>

## Onion 套件工具

可透過下列指令安裝:

    curl http://install.onionphp.org/ | bash

<https://github.com/c9s/Onion>

## Database

For MySQL:

    $ mysql -uroot -p 
    > create database phifty charset utf8;
    > grant all priviliges on phifty@localhost to phifty.* identified by 'phifty';

For postgresql:

    $ sudo -u postgres createuser -P phifty
    $ sudo -u postgres createdb phifty


## ORM Database

LazyRecord 需求:

1. PDO
2. MySQL, SQLite 或 PostgreSQL

安裝 LazyRecord

    $ curl -O http://build.corneltek.com/job/LazyRecord/ws/lazy
    $ chmod +x lazy
    $ cp lazy /usr/bin/

執行 lazy:

    $ lazy --version

## Setup Phifty Framework Development Environment

Clone this repository (checkout develop branch):

    git clone git@git.corneltek.com:phifty.git
    cd phifty

安裝 Phifty 相依套件 (PEAR 以及 Extensions)

    scripts/install-php-deps

如果您使用 phpbrew, 非系統 PHP 則可以直接執行 enable 命令來啟用這些 extension (若有用到的話),
在這邊建議至少啟用 APC, 在性能上表現會比較好。

    phpbrew enable apc
    phpbrew enable yaml
    phpbrew enable memcache
    phpbrew enable mongo
    # 手動去 php.ini 底下加入 zend_extension={absolute path}/xdebug.so

若您使用系統 PHP，可在 php.ini 檔內新增設定:

    extension=apc.so
    extension=yaml.so

Install vendor packages from git repositories:

    scripts/install
    onion -d install

Modify config files:

    config/application.yml     - application config file (Main Application Config).
    config/framework.yml       - framework config file (Apps, Plugins, Locale, Application name ... etc).
    config/testing.yml        - database config file.
    db/config/database.yml        - database config file.

These 4 config files have 4 sections: `application`, `framework`, `database` and `testing`

To change config environment type, you can export `PHIFTY_ENV` environment variable
in your shell.

    export PHIFTY_ENV=development

Copy the default development config files:

    cp -v config/framework.dev.yml      config/framework.yml
    cp -v config/application.dev.yml    config/application.yml
    cp -v config/testing.dev.yml        config/testing.yml
    cp -v db/config/database.default.yml   db/config/database.yml

Edit your db/config/database.yml for your local database connection:

    data_sources:
      default:
        driver: mysql
        host: localhost
        database: phifty
        user: root
        pass: 123123

Then build your config file for lazyrecord:

    lazy build-conf db/config/database.yml

Then edit your config/framework.yml

If you're running phifty, you **don't** need to change the UUID section.

If you're creating new web application with phifty, you should also change the default
UUID, application name, application id, domain in the `config/framework.yml`
file:

    ApplicationName: YourApp
    ApplicationID:   yourapp
    ApplicationUUID: 9fc933c0-70f9-11e1-9095-3c07541dfc0c
    Domain: yourapp.dev

To get a new UUID from command-line (for new web application):

    $ uuid
    e36f9048-d6d3-11e1-a052-3c07541dfc0c

After modifing config files, please run build-conf command to build php config files:

    $ php bin/phifty build-conf

The above command generates *.php files:

    config/framework.php
    config/application.php
    config/testing.php

Initialize assets (this installs asset files into webroot/assets):

    $ php bin/phifty asset --link

Build database table SQL from schemas

    $ lazy build-sql --rebuild

Build basedata, (this step is required to create default data):

    $ lazy build-basedata

Run main.php, see if kernel core bootstrap is fine:

    $ php main.php

See if unit tests passed.

    $ phpunit

## Lighttpd or Nginx

Check [[rewrite]]

## Apache 

### 新增 host

在 /etc/hosts 新增:

    phifty.dev 127.0.0.1

### 設定 Virtual Host

    <VirtualHost *:80>
      ServerName phifty.dev
      DocumentRoot /Users/c9s/git/Work/phifty/webroot
      <Directory /Users/c9s/git/Work/phifty/webroot>
        Options FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        Allow from all
      </Directory>
      CustomLog /opt/local/apache2/logs/phifty.dev/access.log combined
    </VirtualHost>

重啟之後，測試 http://phifty.dev/

