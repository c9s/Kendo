<?php
namespace Kendo\Definition;

abstract class BaseDefinition
{
    protected $identifier;

    protected $label;

    public function __construct($identifier, $label = null)
    {
        $this->identifier = $identifier;
        $this->label = $label ?: $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifiers;
    }

    public function getLabel()
    {
        return $this->label;
    }
}



