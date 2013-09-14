
CRUD
=======

URL mapping:

    /bs/news/crud/list
    /bs/news/crud/item  (per row)
    /bs/news/crud/create
    /bs/news/crud/edit

### Steps

Add Admin menu item:

	$menu = \AdminUI\Menu::getInstance();
	$menu->add('News', array( 'href' => '/bs/news'));

mount CRUDRouterSet in your router table.

	$this->expandRoute( '/bs/news', 'News_CRUDRouterSet' );

define CRUD attributes

	class YourCRUDRouterSet ... 
	{
		/* CRUD Attributes */
		public $namespace  = 'News';
		public $modelClass = '\News\Model\News';
		public $modelName  = 'News';
		public $crudId     = 'news';

and template scope

    public $templateScope = array('plugin:News' , 'plugins:AdminUI' );


CRUD Action
===========

