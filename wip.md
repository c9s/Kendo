Todo
====

- refactor Kendo schema initialization to a single command.



- chief model and management.
- dropdown menu.
- recipe pdf export.
- recipe print export.

Working in progress
===================


x Refactor Action View Layout class, reduce duplication from AdminUI\Action\View\StackView
x Provide CRUD dialog (can embed crud/create or crud/edit template and default action-js script for submitting)
x Use CRUD dialog to provide a product\_image upload dialog
x Use CRUD dialog for product file
x Use CRUD dialog for product resource


## CRUD Editor Tabs

- Provide tab registration for Product plugin,
  so that other plugin can register tab into Product
  tabs, there are two types:
    - one or two tabs in one form.
    - one or two tabs in separate form.

Store tab contents in CRUDHandler ?

    public static $tabs;

    $tab = ProductCRUDHandler::addTab('Tab Title' , function() {
        // render content
        return $content;
    });
    $tab->setId('tab-id');

    ProductCRUDHandler::addTab('Tab Title', array('Class','tabMethod') );
    ProductCRUDHandler::removeTab('tab-id2');

- Is there a possible way to detect template files in CRUDHandler ?
    - We need to depends on plugin object or applciation object
      to get the class base directory.
    - Use twig file system api to check template.


x Nested Action
    - Target:
        - Backend: Render fields with hash.
            - Takes values from hash.
        - Frontend: Use hash key to aggregate data.
    - Add a new key (nested attribute) to Action, so that when rendering params, 
      we can render {table name}[ {field name} ].
    - Add support to Action backend run method (take fields from these table)
        - Find actions to execute in these fields.
