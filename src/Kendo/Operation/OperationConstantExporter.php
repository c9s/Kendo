<?php
namespace Kendo\Operation;
use LogicException;
use ReflectionClass;

class OperationConstantExporter
{
    /**
     *
     * @return array[string label]string identifier
     */
    public function export($operationClass)
    {
        $reflection = new ReflectionClass($operationClass);
        $constants = $reflection->getConstants();
        $defined = array();
        foreach ($constants as $name => $value) {
            if (isset($defined[ $value ])) {
                throw new LogicException("Constant '$name' definition use '$value', however '$value' was defined in " . $defined[$value]);
            }
            $defined[ $value ] = $name;
        }
        return $constants;
    }
}
