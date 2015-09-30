<?php
namespace Kendo\Operation;

/**
 * Pre-defined common operations with default labels.
 *
 * OperationClass implements export method can export operation
 * constant name mapping.
 */
class GeneralOperation
{
    /**
     * Constant values are identifier
     */
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const VIEW   = 'view';
    const SEARCH = 'search';


    /**
     * 'export' method export constants in array
     *
     * @return array
     */
    static public function export()
    {
        return [
            'create' => "Create",
            'update' => "Update",
            'delete' => "Delete",
            'view'   => "View",
            'search' => "Search",
        ];
    }
}


