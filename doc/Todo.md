TODO
====

WORKING IN PROGRESS
-------------------

DEVELOPEMENT FEATURES
---------------------

Priority: High

- Support table column migrations.

- Provide a plugin structure generator.

- Provide model class generator.

- Provide action class generator.

- Provide a simple CRUD handler class generator, which generates
  CRUD class from model, and so developers can modify it with their customization.

- Provide a simple global CRUD generation helper, so that we can generate 
  CRUD classes from a simple function call.

- Support multiple action namespace for Model CRUD, eg, for model

    System\Model\Financial\Transaction

  generates classes:

    System\Action\Financial\CreateTransaction
    System\Action\Financial\UpdateTransaction
    System\Action\Financial\DeleteTransaction

- Provide a Search builder that builds a search action and search form.

    - Provide a generic search action that takes column names and operators for searching
    - Provide a search form builder that convert action or model schema to a search form

Priority: Medium

- Refactor Action.js to coffeescript, so that we can maintain it or extend it
  very easily.

- Show error message when Action not found (in front-end)

- Add registable panel to admin ui template, so that components can register their 
  template, button or widgets to the panel. 

  For excel exporter, it can register an "Export to Excel" button to the panel.

Priority: Low

- Provide a simple config cache generator,
  which checks config file mtime to compile YAML format config file 
  to PHP source automatically (in development mode)

    $compiled = ConfigKit::compile('source_file.yml' , 'compiled_file.php');
    $config = ConfigKit::load('source_file.yml', 'compiled_file.php');
    $config = ConfigKit::load('source_file.yml');

- Provide a simple requirement checking script,
  for checking required extensions and vendor classes.

- Provide a Debug toolbar that shows "request time", "memory usage", "sql queries",
  "loaded extensions", "loaded classes", "request variables" informations.

- Let LazyRecord and register callback to events, so that 
  We can record the sql queries for debug toolbar.

- Support model bootstrap dependency.


USER FEAUTURES
--------------

- Provide a generic ExcelExporter for model CRUDs

UI FEATURES
-----------

- jQuery context menu integration http://medialize.github.com/jQuery-contextMenu/demo.html

PERFORMANCE
-----------

- Improve production mode asset compilation.
- Improve production mode asset cache.
- Improve production mode collection, model cache with memcached.

Problems
--------
* Plugin name.

* Update record data with NULL ???

* Pager LinkText
* Plugin class prefix, with namespace. should be re-designed.
* Validator re-design
* Action result re-design.
* Action result template support.
* Action button renderer?
* System Action

    * Flush Cache
    * Flush Gettext Cache

* Action js theme

DONE
===================
x Model Join
x ideal locale usage:

    kernel()->locale->current;
    kernel()->locale->available;

x Action View
x Form Widgets
    - Column Render (TD,TR/DIV)
        - Label Widget
        - Field Widget
            - Text Widget
            - Radios Widget
            - Option Widget
            - Password Widget
            - ReadOnly Widget
            - Plain Widget  (plain text)
            - Date Widget
            - Time Widget
            - DateTime Widget

x Development error page

    Action not found.
    Template error
    Extension not found.
    Syntax error ... 
    etc.
