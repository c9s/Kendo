<?php
namespace Kendo;
use ReflectionClass;

class ConstantExporter
{
    public function export($operationClass)
    {
        $reflection = new ReflectionClass($operationClass);
        return $reflection->getConstants();
    }
}
