<?php
namespace SimpleApp;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use Kendo\Operation\GeneralOperation;

class SimpleSecurityPolicy extends RBACSecurityPolicySchema
{
    public function schema()
    {
        $this->resource('books', 'Book')->operations(new GeneralOperation);
        $this->resource('products', 'Product')->operations(new GeneralOperation);

        // The basic actor - user
        $this->actor('user', 'User')
            ->role('admin', 'Administrator')
            ->role('member', 'Member')
            ->role('customer', 'Customer')
            ;

        // Actor without roles
        $this->actor('bank', 'Bank');

        // Actor without roles
        $this->actor('store', 'Store');

        // Define global available operations
        $this->operations(new GeneralOperation);

        $this->rule()
            ->actor('user')->role('admin')
                ->can([GeneralOperation::CREATE, GeneralOperation::UPDATE, GeneralOperation::DELETE], 'books');

        $this->rule()
            ->actor('user')
                ->role('admin')
                ->can([GeneralOperation::CREATE, GeneralOperation::UPDATE, GeneralOperation::DELETE], 'products');

        $this->rule()
            ->actor('user')
                ->role('customer')
                ->can([GeneralOperation::VIEW], 'products')
                ->cant([GeneralOperation::CREATE], 'products');

        // all user actor can view and search
        $this->rule()
            ->actor('user')
                ->can([GeneralOperation::VIEW, GeneralOperation::SEARCH], 'products');
    }
}
