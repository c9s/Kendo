<?php
namespace Kendo\Acl;
use Kendo\Model\AccessResource;
use Kendo\Model\AccessResourceCollection;
use Kendo\Model\AccessRule;
use Kendo\Model\AccessRuleCollection;
use Kendo\Model\AccessControl;
use Kendo\Model\AccessControlCollection as ACCollection;
use Kendo\Acl\Rule;
use Kendo\Acl\Resource;
use Exception;

/**
 * Use access control rules from database.
 */
abstract class DatabaseRules extends BaseRules
{
    public $autoSync = false;

    public function __construct() 
    {
        $this->cacheSupport = extension_loaded('apc');
        if ($this->cacheEnable && $this->cacheSupport) {
            $key = get_class($this);
            if ($cache = apc_fetch($key) ) {
                $this->import($cache);
                $this->cacheLoaded = true;
                return;
            } elseif ($this->autoSync) {
                $this->buildAndSync();
                apc_store($key,$this->export(), $this->cacheExpiry);
            }
        } elseif ($this->autoSync) {
            $this->buildAndSync();
        }
    }

    public function getRuleRecordArguments(Rule $rule)
    {
        $args = array( 
            'rules_class' => get_class($this),
            'resource' => $rule->resource,
            'operation' => $rule->operation['id'],
            'description' => $rule->desc,
        );
        if (isset($rule->operation['label'] )) {
            $args['op_name'] = $rule->operation['label'];
        }
        return $args;
    }


    /**
     * Sync Rule item to database.
     */
    public function syncRule(Rule $rule) {
        // sync resource operation table
        $ar = new AccessRule;
        $ret = $ar->createOrUpdate( $this->getRuleRecordArguments($rule) ,array('resource','operation'));
        if (! $ret->success) {
            throw new $ret->exception;
        }

        $ac = new AccessControl;
        $ret = $ac->createOrUpdate(array( 
            'rule_id' => $ar->id,
            'role' => $rule->role,
            'allow' => $rule->allow,
        ), [ 'rule_id', 'role' ]);
        if (! $ret->success) {
            throw new $ret->exception;
        }

        // override default allow values
        $rule->allow = $ac->allow;
    }

    public function syncResource(Resource $res)
    {
        $resource = new AccessResource;
        $ret = $resource->createOrUpdate( array(
            'name' => $res->name,
            'label' => $res->label,
            'rules_class' => get_class($this),
        ),array('name'));

        if (! $ret->success) {
            throw $ret->exception;
        }
    }

    /**
     * load rules from database
     */
    public function buildAndSync() {
        $this->build();  // initialize rules from code
        $this->write();  // write back to database
    }

    public function getAccessRuleRecords()
    {
        $rules = new AccessRuleCollection;
        $rules->where()
            ->equal('rules_class',get_class($this));
        return $rules;
    }

    public function getResourceRecords()
    {
        $resources = new AccessResourceCollection;
        $resources->where()
            ->equal('rules_class',get_class($this));
        return $resources;
    }

    /**
     * Load rules from database.
     */
    public function load() 
    {
        $resources = $this->getResourceRecords();
        foreach ($resources as $resource) {
            $res = $this->resource($resource->name);
            if ($resource->label) {
                $res->label($resource->label);
            }
        }

        $rules = $this->getAccessRuleRecords();
        $loaded = false;
        foreach ($rules as $rule) {
            $control = $rule->control;
            if ($control->allow) {
                $this->addAllowRule($control->role,$rule->resource,$rule->operation);
            } else {
                $this->addDenyRule($control->role,$rule->resource,$rule->operation);
            }
            $loaded = true;
        }
        return $loaded;
    }

    public function clean() 
    {
        $rules = $this->getAccessRuleRecords();
        foreach ($rules as $rule) {
            $rule->control->delete();
            $rule->delete();
        }
    }

    public function write() {
        foreach ($this->resources as $res) {
            $this->syncResource($res);
        }
        foreach( $this->rules as $rule ) {
            $this->syncRule($rule);
        }
    }

}


