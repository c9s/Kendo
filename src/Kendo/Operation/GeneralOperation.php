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
    const VIEW   = 'View';
    const CREATE = 'Create';
    const UPDATE = 'Update';
    const DELETE = 'Delete';
    const SEARCH = 'Search';


    /**
     * 'export' method export constants in array
     *
     * @return array
     */
    static public function export()
    {
        return [
            'CREATE' => "Create",
            'UPDATE' => "Update",
            'DELETE' => "Delete",
            'VIEW'   => "View",
            'SEARCH' => "Search",
        ];
    }
}


