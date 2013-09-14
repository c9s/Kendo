Phifty Structure
================

WebApp 主要圍繞著 Kernel (`Phifty\Kernel`) 運作，Kernel 可以掛載許多服務
如 MemcacheService, LocaleService, CacheService, SessionService 等等。

Kernel 為 Singleton 只有一個物件，可以透過 kernel() 來取得 Kernel。

一個 Kernel 可以有多個 Application (可以在掛載於不同路徑下)。
每個 Application (`Phifty\Bundle`) 會有各自的 Action, Model, Controller, Template, Web Resource.

每個 Application 會有一個 `init` method ，裡面可設置初始化 (例如 Routing)

一個 Kernel 可以有多個 Plugin，每個 Plugin 會有各自的 Action, Model, Controller, Template, Web Resource.

    Kernel
        -> many applications
            -> appA
                -> Model
                -> Action
                -> Controller
                -> templates.
                -> web resource files.
            -> appB ...
            -> appC ...
        -> many services (database, memcached, locale, ... etc)
        -> many plugins
            -> pluginA
                -> templates.
                -> web resource files.
        -> one webroot
            link application web dir to ph/{appName}
            link plugin web dir to ph/plugins/{pluginName}


Framework 目錄結構
=========================

phifty.git clone 下來後，就是一個 Application ，可以獨立運作，也可以透過
git submodule 的方式來使用 phifty.git repository (從外部掛載 Phifty 的
Core\Application)

|Path               |description             |
|-------------------|------------------------|
|main.php           |主程式進入點            |
|config/            |設定檔目錄              |
|applications/      |應用程式目錄            |
|plugins/           |插件目錄                |
|src/               |Phifty 主程式目錄       |
|webroot/           |HTTP 伺服器進入點       |

* 每個 Application (Phifty\Bundle) 被放置於 `applications/` 目錄下。

* Kernel (Phifty\Kernel) 被放置於 `src/Phifty/Kernel.php`。

* 每個 Plugin (Phifty\Plugin) 被放置於 `plugins/` 目錄下。

* `webroot/index.php` HTTP 服務進入點，載入 `main.php` ，並且進行路徑分配。

* `main.php` 主程式進入點，定義了 `kernel()` 載入 config file 並且載入 service。


## Config File Structure

設定檔結構:

|config file           |description         |
|----------------------|--------------------|
|config/framework.yml  |框架設定檔          |
|config/app.yml        |應用程式設定檔      |
|config/database.yml   |資料庫設定檔        |

每個設定檔皆為三個部分: development, production, testing。

* development: 開發環境設定 (主要設定)
* production:  上線環境設定
* testing:     測試環境設定

可以透過下列 API 取得設定檔:

<?php
    kernel()->config->get('framework','ApplicationName');
    kernel()->config->get('framework','View.Backend');
    kernel()->config->framework->ApplicationName;
    kernel()->config->framework; // Phifty\Config\Accessor object
    kernel()->config->application; // Phifty\Config\Accessor object
    kernel()->config->database; // Phifty\Config\Accessor object
?>

## Framework Flow (HTTP Request)

* 先進入 webroot/index.php
* 載入 main.php
* 載入 applications
* 載入 service (服務)
* 載入 Plugins
* 載入 Environment bootstrap Script (src/Phifty/Environment/*): production, development, command-line 等等
* 路徑分派

## Framework Flow (Command Line)

* php scripts/phifty.php 或 php phifty
* 進入 main.php
* 進入 Phifty\Console
* 分派至 Phifty\Command\*

## Phifty export commmand

Init command should parse dirs from `plugins` dir and `app`, `core` dirs.

AppSpace
=================

For action class names like:

    new App\Action\CreateUser
    new App\Action\UpdateUser

should look up actino files in app:

    app/action/CreateUser.php
    app/action/UpdateUser.php

and the names in form:

    App::Action::CreateUser
    App::Action::UpdateUser

for phifty core action:

    Phifty::Action::Redirect


For model names like:

    App::Model::Product

should look up files in app/model

    app/model/Product.php


App Controller

    App\Controller\Index
    App\Controller\AddUser
    App\Controller\RemoveUser


Plugin
===============

* Requirements
** Plugin can inject content to a page header.
** Plugin can require its js or css file from its plugin/web dir.
** Plugin can have its model, controller, action, view (template)

To add a plugin, edit etc/config.yml first.

    - plugins:
        - SB

Your plugin structure is like:

    plugins/sb/Model/
    plugins/sb/Action/
    plugins/sb/Controller/
    plugins/sb/View/
    plugins/sb/config/config.yml (not support yet)

Product class extends from \Phifty\Plugin\Plugin.

    init()
    pageStart()
    pageEnd()

For action name with plugin

    SB\Action\CreateProduct
    SB\Action\UpdateProduct
    SB\Action\DeleteProduct

should look up

    plugins/SB/Action/CreateProduct.php
    plugins/SB/Action/UpdateProduct.php
    plugins/SB/Action/DeleteProduct.php

For model name with plugin

    SB\Model\Product

should look up:

    plugins/SB/Model/Product.php

Model name with plugin name (Small Business Plugin)

    SB\Model\Product
    SB\Model\ProductCategory
    SB\Model\Order
    SB\Model\OrderItem

    PluginName\Controller\Index
    PluginName\Controller\Back

