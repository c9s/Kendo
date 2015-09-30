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
            'a' => 'A',
            'b' => 'B',
            'c' => 'C',
        ], $constants);
    }


    /**
     * @expectedException LogicException
     */
    public function testExpotDuplicateValue()
    {
        $exporter = new OperationConstantExporter;
        $constants = $exporter->export('TestFooDuplicatedConstants');
        $this->assertSame([ 
            'A' => 0,
            'B' => 1,
            'C' => 2,
        ], $constants);
    }


}

