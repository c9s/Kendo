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
            ->operations([ GeneralOperation::CREATE, GeneralOperation::UPDATE ]);

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
                ->can([GeneralOperation::CREATE, GeneralOperation::UPDATE, GeneralOperation::DELETE], 'books');

        // This will expand to 3 rules
        $this->rule()
            ->actor('user')
                ->role('admin')
                ->can([GeneralOperation::CREATE, GeneralOperation::UPDATE, GeneralOperation::DELETE], 'products');

        // This will expand to 2 rules
        $this->rule()
            ->actor('user')
                ->role('customer')
                ->can([GeneralOperation::VIEW], 'products')
                ->cant([GeneralOperation::CREATE], 'products');

        // This will expand to 2 rules
        // all user actor can view and search
        $this->rule()
            ->actor('user')
                ->can([GeneralOperation::VIEW, GeneralOperation::SEARCH], 'products');
    }
}
