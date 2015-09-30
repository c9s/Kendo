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

        $constants = $reflection->getConstants();
        $defined = array();
        foreach ($constants as $constantName => $identifier) {
            if (isset($defined[$identifier])) {
                throw new LogicException("Constant '$constantName' definition use '$identifier', however '$identifier' was defined in " . $defined[$identifier]);
            }
            // We use Constant name as the default label.
            $defined[$identifier] = $constantName;
        }
        return $defined;
    }
}
