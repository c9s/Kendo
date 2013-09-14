<?php
namespace TestRules {
    class FooRules extends \Kendo\Acl\BaseRules { 
        public function build() {
            $this->resource('user');
            $this->rule('foo','user','create',true);
        }
    }

    class BarRules extends \Kendo\Acl\BaseRules {
        public function build() {
            $this->resource('news');
            $this->rule('bar','news','update',true);
        }
    }
}

namespace {
    class RuleLoaderTest extends PHPUnit_Framework_TestCase
    {
        function test()
        {
            $loader = new Kendo\Acl\RuleLoader;
            $loader->load('TestRules::FooRules');
            $loader->load('TestRules::BarRules');
            ok($loader);

            is(true, $loader->authorize('foo','user','create') );
            is(false, $loader->authorize('foo','user','update') );
            is(true, $loader->authorize('bar','news','update') );
            is(false, $loader->authorize('bar','news','delete') );

            // for undefined resource, returns true (by default)
            is(true, $loader->authorize('bar','roles','create') );
        }
    }
}
