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
    const ALL    = 61;
    const CREATE = 1;       // 00001
    const UPDATE = 2; // 1 << 2;  // 00010
    const DELETE = 4; // 1 << 3;  // 00100
    const VIEW   = 8; // 1 << 4;  // 01000
    const SEARCH = 16; // 1 << 5;  // 10000
}


