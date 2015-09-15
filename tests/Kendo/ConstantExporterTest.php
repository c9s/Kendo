<?php
use Kendo\ConstantExporter;

class FooConstants
{
    const A = 0;
    const B = 1;
    const C = 2;
}

class ConstantExporterTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $exporter = new ConstantExporter;
        $constants = $exporter->export('FooConstants');
        $this->assertSame([ 
            'A' => 0,
            'B' => 1,
            'C' => 2,
        ], $constants);
    }
}

