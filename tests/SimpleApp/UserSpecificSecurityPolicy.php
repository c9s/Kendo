<?php
namespace SimpleApp;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use Kendo\Operation\GeneralOperation;

class UserSpecificSecurityPolicy extends RBACSecurityPolicySchema
{
    public function schema()
    {
        // Define global available operations,
        // When resource didn't defined its own operations, it inherits from global operations
        $this->globalOperations(new GeneralOperation);

        $coreGroup = $this->resourceGroup('core', 'Core');

        $this->resource('books', 'Book')
            ->group($coreGroup)
            ->operations([ GeneralOperation::CREATE, GeneralOperation::UPDATE, GeneralOperation::DELETE ]);

        $this->resource('products', 'Product')
            ->group($coreGroup)
            ->useGlobalOperations()
            ;


        // The actor with different roles - user
        $this->actor('user', 'User')
            ->role('admin', 'Administrator')
            ->role('member', 'Member')
            ->role('customer', 'Customer')
            ;

        $this->rule()
            ->actor('user', 1)
            ->can([GeneralOperation::CREATE], 'books');

        $this->rule()
            ->actor('user', 2)
            ->can([GeneralOperation::UPDATE], 'books');

        $this->rule()
            ->actor('user', 3)
            ->can([GeneralOperation::DELETE], 'books');
    }
}
