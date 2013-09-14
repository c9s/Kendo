Upgrading
=========

Commit: merge 4dab673 (site/ctravel) 37db74d (master)

    commit 65e5fc1e4521a12bbf350b5f2ee285f7ee688f16
    Merge: 4dab673 37db74d
    Author: c9s <cornelius.howl@gmail.com>
    Date:   Mon Aug 13 11:55:39 2012 +0800

        Merge remote-tracking branch 'origin/master' into site/ctravel


Encoding Problem
----------------

Because the old LazyRecord didn't set INIT command with set names utf8;

So the bytes were stored as latin1, we need to dump database and skip charset and 
re-import it to the new database (with utf8).

First, dump the database with latin1 charset

    export DB=lart_ctravel
    mysqldump -uroot -p --default-character-set=latin1 -c --skip-set-charset $DB > $DB.sql

Append set names utf8 command

    set names utf8

Drop the original database:

    mysql -uroot -p $DB
    > DROP DATABASE {{$DB}};

Create new database:

    $ mysql -uroot -p
    > CREATE DATABASE lart_ctravel charset UTF8;

Reimport SQL

    mysql -uroot -p $DB < $DB.sql

Code Changes
-----------------
- Rename Phifty\Plugin to Phifty\Plugin\Plugin

- CRUDHandler methods

    - crud_list_prepare to listRegionActionPrepare
    - crud_list to listRegionAction
    - crud_edit to editRegionAction
    - crud_edit_prepare to editRegionActionPrepare

    <?php

    function listRegionActionPrepare()
    {
        $category = new Category;
        $categoryCollection = new CategoryCollection;
        $categoryId = (int) $this->request->param('category_id');
        $category->load( $categoryId );

        $collection = $this->getCollection();
        $pager = $this->createCollectionPager($collection);

        $data = array(
            'Object' => $this,
            'Items' => $collection->items(),
            'Pager' => $pager,
            'Title' => $this->getListTitle(),
            'Columns' => $this->getListColumns(),
        );
        foreach( $data as $k => $v ) {
            $this->vars['CRUD'][ $k ] = $v;
        }
        $this->assignVars(array(
                'categoryItems'   => $categoryCollection->items(),
                'categoryId'      => $categoryId,
                'categoryCurrent' => $category,
        ));
        return $this->renderCrudList();
    }

    ?>

- Remove CRUD namespace member, it's parsed automatically.

    public $namespace  = 'Product';
    public $modelName  = 'Category';

- Schema Files: Rename defaultBuilder to default

- RecordAction namespace fix, rename belows to:

    ActionKit\CreateRecordAction
    ActionKit\UpdateRecordAction
    ActionKit\DeleteRecordAction

    ActionKit\RecordAction\CreateRecordAction
    ActionKit\RecordAction\UpdateRecordAction
    ActionKit\RecordAction\DeleteRecordAction


Add useRecordSchema to RecordActions

    $this->useRecordSchema();



File Changes
-----------------

- Add AssetService to framework.yml

    ln -s phifty/assets assets
    cp phifty/.assetkit
    php phifty/bin/phifty build-conf
    php phifty/bin/phifty asset
    cp phifty/webroot/index.php webroot/index.php


