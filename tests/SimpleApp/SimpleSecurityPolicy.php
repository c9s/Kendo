<?php
namespace SimpleApp;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use Kendo\Operation\GeneralOperation;

class SimpleSecurityPolicy extends RBACSecurityPolicySchema
{
    public function schema()
    {
        // Define global available operations,
        // When resource didn't defined its own operations, it inherits from global operations
        $this->globalOperations(new GeneralOperation);

        $coreGroup = $this->resourceGroup('core', 'Core');

        $this->resource('books', 'Book')
            ->group($coreGroup)
            ->operations([ 'create', 'update' ]);

        $this->resource('products', 'Product')
            ->group($coreGroup)
            ->operations(new GeneralOperation);


        // The actor with different roles - user
        $this->actor('user', 'User')
            ->role('admin', 'Administrator')
            ->role('member', 'Member')
            ->role('customer', 'Customer')
            ;

        // Actor without roles
        $this->actor('bank', 'Bank');

        // Actor without roles
        $this->actor('store', 'Store');

        // This will expand to 3 rules
        $this->rule()
            ->actor('user')->role('admin')
                ->can(['create', 'update', 'delete'], 'books');

        // This will expand to 3 rules
        $this->rule()
            ->actor('user')
                ->role('admin')
                ->can(['create', 'update', 'delete'], 'products');

        // This will expand to 2 rules
        $this->rule()
            ->actor('user')
                ->role('customer')
                ->can(['view'], 'products')
                ->cant(['create'], 'products');

        // This will expand to 2 rules
        // all user actor can view and search
        $this->rule()
            ->actor('user')
                ->can(['view', 'search'], 'products');
    }
}
