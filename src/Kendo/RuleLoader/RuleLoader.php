<?php
namespace Kendo\RuleLoader;
use Kendo\Definition\Definition;
use SplObjectStorage;

class RuleLoader
{

    /**
     * @var SplObjectStorage
     */
    protected $definitions;

    /**
     * @array accessControlList[actor][role][resource] = [ CREATE, UPDATE, DELETE ];
     */
    protected $accessControlList = array();



    public function __construct()
    {
        $this->definitions = new SplObjectStorage;
    }

    public function load(Definition $definition)
    {
        if ($this->definitions->contains($definition)) {
            return false;
        }
        $this->definitions->attach($definition);

        // Build control list
        $rules = $definition->getRules();
        foreach ($rules as $rule) {
        }
    }
}



