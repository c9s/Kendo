<?php
namespace Kendo\RuleLoader;
use Kendo\RuleLoader\RuleLoader;
use PDO;

class PDORuleLoader implements RuleLoader
{
    public function __construct()
    {

    }

    public function load(PDO $conn)
    {
        // $conn->query('');
    }
}

