<?php

class AclTestRules extends Kendo\Acl\BaseRules { 
    public function build() {
        $this->resource('user')
            ->label('User Management');

        $this->resource('news')
            ->label('News Management');

        $this->rule('admin','user','create',true);
        $this->rule('admin','user','update',true);
        $this->rule('admin','user','delete',true);
        $this->rule('user','user','create',false);
        $this->rule('user','user','update',false);
        $this->rule('user','user','delete',false);
    }
}

class BaseRulesTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $rules = new AclTestRules;
        ok($rules);
        ok($rules->allowRules);
        ok($rules->denyRules);
        is( true, $rules->authorize('admin','user','create') );
        is( true, $rules->authorize('admin','user','update') );
        is( true, $rules->authorize('admin','user','delete') );
        is( null, $rules->authorize('admin','user','hackhack') );
        is( false,$rules->authorize('user','user','create') );
    }
}

