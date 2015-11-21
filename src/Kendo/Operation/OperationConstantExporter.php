<?php
namespace Kendo\Operation;
use LogicException;
use ReflectionClass;
use ReflectionObject;

class OperationConstantExporter
{
    /**
     *
     * @return array[string label]string identifier
     */
    public function export($operationClass)
    {
        if (is_object($operationClass)) {
            if (method_exists($operationClass, 'export')) {
                return $operationClass->export();
            }
        }

        $reflection = new ReflectionClass($operationClass);
        if ($reflection->hasMethod('export')) {
            $method = $reflection->getMethod('export');
            if ($method->isStatic()) {
                return $operationClass::export();
            } else {
                $op = new $operationClass;
                return $op->export();
            }
        }

        $map = [ ];
        $constants = $reflection->getConstants();
        foreach ($constants as $key => $val) {
            $map[ strtolower($key) ] = $val;
        }
        return $constants;
        /*
        // rewrite constant names with class name
        $fullqualifiedConstants = [];
        foreach ($constants as $identifier => $label) {
            $fullqualifiedConstants[ $reflection->getShortName() . ':' . $identifier] = $label;
        }
        return $fullqualifiedConstants;
         */
    }
}
