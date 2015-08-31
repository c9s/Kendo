<?php
namespace Kendo\Definition;
use LazyRecord\BaseModel;

class BaseDefinition
{
    /**
     * @var id is used for PDO fetched object
     */
    public $id;

    public $identifier;

    public $label;

    public $description;

    /**
     * @var BaseModel The record object existing in database.
     */
    protected $record;


    public function __construct($identifier = null, $label = null)
    {
        if ($identifier) {
            $this->identifier = $identifier;
        }
        if ($label) {
            $this->label = $label;
        }
        if ($this->id) {
            $this->id = intval($this->id);
        }
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setRecord(BaseModel $record)
    {
        $this->record = $record;
    }

    public function getRecord()
    {
        return $this->record;
    }

}



