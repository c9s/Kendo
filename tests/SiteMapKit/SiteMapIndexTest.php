<?php

class SiteMapIndexTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $index = new SiteMapKit\SiteMapIndex;
        ok($index);
    }
}

