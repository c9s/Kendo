<?php
require_once 'src/ProcessPipe.php';

class ProcessPipeTest extends PHPUnit_Framework_TestCase 
{
    function testProcessPipe()
    {
        $proc = new ProcessPipe;
        $output = $proc( 'ls -1' );
        ok( $output->stdout );
        is( '' , $output->stderr );
        is( 0, $output->return );
    }
}


