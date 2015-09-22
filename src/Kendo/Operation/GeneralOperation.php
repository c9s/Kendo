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
    // const ALL    = 1 | 1 << 2 | 1 << 3 | 1 << 4 | 1 << 5; // 11111
    const ALL    = 'all';
    const CREATE = 'create';       // 00001
    const UPDATE = 'update'; // 1 << 2;  // 00010
    const DELETE = 'delete'; // 1 << 3;  // 00100
    const VIEW   = 'view'; // 1 << 4;  // 01000
    const SEARCH = 'search'; // 1 << 5;  // 10000
}


