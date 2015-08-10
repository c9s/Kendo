<?php
namespace Kendo;
use Kendo\Definition\Definition;
use SplObjectStorage;

class DefinitionStorage extends SplObjectStorage
{
    /**
     * @var SplObjectStorage
     */
    protected $definitionObjects;


    public function __construct(array $defs = array())
    {
        foreach ($defs as $def) {
            $this->attach($def);
        }
    }

    public function add(Definition $definition)
    {
        if ($this->contains($definition)) {
            return false;
        }
        $this->attach($definition);
    }

}



