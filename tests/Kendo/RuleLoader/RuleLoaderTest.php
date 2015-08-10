<?php
use Kendo\RuleLoader\RuleLoader;
use Kendo\Definition\Definition;
use Kendo\Operation\CommonOperation as Op;

class SimpleDefinition extends Definition
{
    public function schema()
    {
        $this->actor('user', 'User')
            ->roles('admin', 'user', 'customer');

        $this->rule()
            ->actor('user')
            ->roles('admin')
                ->can([Op::CREATE, Op::UPDATE, Op::DELETE], 'books');

        $this->rule()
            ->actor('user')
                ->roles('admin')
                ->can([Op::CREATE, Op::UPDATE, Op::DELETE], 'products');

        $this->rule()
            ->actor('user')
                ->roles('user')
                ->can([Op::VIEW, Op::SEARCH], 'products');

        $this->rule()
            ->actor('user')
                ->roles('customer')
                ->can([Op::VIEW], 'products');
    }
}

class RuleLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testRuleLoader()
    {
        $loader = new RuleLoader;
        $loader->load(new SimpleDefinition);
    }
}

