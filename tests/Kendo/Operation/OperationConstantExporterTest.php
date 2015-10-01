<?php
use Kendo\Operation\OperationConstantExporter;

class TestFooConstants
{
    const A = 'a';
    const B = 'b';
    const C = 'c';
}

class TestFooDuplicatedConstants
{
    const A = 'a';
    const B = 'c';
    const C = 'c';
}

class OperationConstantExporterTest extends PHPUnit_Framework_TestCase
{
    public function testExport()
    {
        $exporter = new OperationConstantExporter;
        $constants = $exporter->export('TestFooConstants');
        $this->assertSame([ 
            'A' => 'a',
            'B' => 'b',
            'C' => 'c',
        ], $constants);
    }
}

