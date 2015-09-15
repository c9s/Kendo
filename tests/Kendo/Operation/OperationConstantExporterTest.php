<?php
use Kendo\Operation\OperationConstantExporter;

class TestFooConstants
{
    const A = 0;
    const B = 1;
    const C = 2;
}

class TestFooDuplicatedConstants
{
    const A = 0;
    const B = 0;
    const C = 0;
}

class OperationConstantExporterTest extends PHPUnit_Framework_TestCase
{
    public function testExport()
    {
        $exporter = new OperationConstantExporter;
        $constants = $exporter->export('TestFooConstants');
        $this->assertSame([ 
            'A' => 0,
            'B' => 1,
            'C' => 2,
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

