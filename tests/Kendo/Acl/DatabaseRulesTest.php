<?php
namespace DBTestRules;

class FooRules extends \Kendo\Acl\DatabaseRules
{
    public function build()
    {
        $this->resource('product')
                ->label('Product');

        $this->rule('admin','product','create',true);
        $this->rule('admin','product','update',false);
    }

}

namespace main;

class DatabaseRulesTest extends \PHPUnit_Framework_TestCase
{

    public function testDatabaseRulesWithApc() {
        if ( ! ini_get('apc.enable_cli') ) {
            skip("You must enable apc.enable_cli to test this");
        }

        $loader = new \Kendo\Acl\RuleLoader;
        ok($loader);

        $rules = $loader->load('DBTestRules::FooRules');
        $rules->buildAndSync();

        ok( $rules,'Rules loaded' );
        ok( $loader->authorize('admin','product','create') , "Admin Can Create Product" );
        ok( ! $loader->authorize('admin','product','update') , "Admin Can Not Update Product" );
        ok( ! $rules->cacheLoaded );

        ok( $loaded = $rules->load() , 'can be re-loaded from database' );

        // this one should load rules from cache
        $rules2 = new \DBTestRules\FooRules;
        ok( $rules2 );
        ok( $rules2->authorize('admin','product','create') );
        ok( ! $rules2->authorize('admin','product','update') );
        $rules->clean();

    }

    public function testDatabaseRules()
    {
        $loader = new \Kendo\Acl\RuleLoader;
        ok($loader);

        $rules = $loader->load('DBTestRules::FooRules');
        $rules->buildAndSync();

        ok( $rules,'Rules loaded' );
        ok( $loader->authorize('admin','product','create') , "Admin Can Create Product" );
        ok( ! $loader->authorize('admin','product','update') , "Admin Can Not Update Product" );
        ok( ! $rules->cacheLoaded );

        ok( $loaded = $rules->load() , 'can be re-loaded from database' );
    }

}

