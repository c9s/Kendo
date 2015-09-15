<?php
use Kendo\Operation\OperationConstantExporter;

class FooConstants
{
    const A = 0;
    const B = 1;
    const C = 2;
}

class OperationConstantExporterTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $exporter = new OperationConstantExporter;
        $constants = $exporter->export('FooConstants');
        $this->assertSame([ 
            'A' => 0,
            'B' => 1,
            'C' => 2,
        ], $constants);
    }
}

