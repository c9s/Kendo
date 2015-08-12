<?php
namespace SimpleApp;
use Kendo\SecurityPolicy\RBACSecurityPolicySchema;
use Kendo\SecurityPolicy\SecurityPolicyModule;
use Kendo\Operation\CommonOperation as Op;

class SimpleSecurityPolicy extends RBACSecurityPolicySchema
{
    public function schema()
    {
        $this->resource('books', 'Book');
        $this->resource('products', 'Product');

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

        // Define available operations
        $this->operations(new Op);

        $this->rule()
            ->actor('user')->role('admin')
                ->can([Op::CREATE, Op::UPDATE, Op::DELETE], 'books');

        $this->rule()
            ->actor('user')
                ->role('admin')
                ->can([Op::CREATE, Op::UPDATE, Op::DELETE], 'products');

        $this->rule()
            ->actor('user')
                ->role('customer')
                ->can([Op::VIEW], 'products')
                ->cant([Op::CREATE], 'products');

        // all user actor can view and search
        $this->rule()
            ->actor('user')
                ->can([Op::VIEW, Op::SEARCH], 'products');
    }
}
