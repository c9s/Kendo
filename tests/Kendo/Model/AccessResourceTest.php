<?php

class AccessResourceTest extends PHPUnit_Framework_TestCase
{
    function test()
    {
        $resource = new Kendo\Model\AccessResource;
        ok($resource);

        $controls = $resource->getControlsByRole('admin');
        $controls->fetch();

        foreach( $controls as $control ) {
            ok($control->access_rule_resource);
            ok($control->access_rule_operation);
        }
    }
}

