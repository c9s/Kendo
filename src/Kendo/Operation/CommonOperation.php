<?php
namespace Kendo\Operation;

/**
 * Pre-defined common operations with default labels.
 */
class CommonOperation
{
    const ALL    = 1 | 1 << 2 | 1 << 3 | 1 << 4 | 1 << 5; // 11111
    const CREATE = 1;       // 00001
    const UPDATE = 1 << 2;  // 00010
    const DELETE = 1 << 3;  // 00100
    const VIEW   = 1 << 4;  // 01000
    const SEARCH = 1 << 5;  // 10000

    public function export()
    {
        return [
            self::ALL    => 'All',
            self::CREATE => 'Create',
            self::UPDATE => 'Update',
            self::DELETE => 'Delete',
            self::VIEW   => 'View',
            self::SEARCH => 'Search',
        ];
    }
}


