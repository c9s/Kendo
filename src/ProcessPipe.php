<?php

class ProcessPipe
{
	function __invoke($cmd,$input = '')
	{
		return $this->execute( $cmd , $input );
	}

    function execute($cmd, $input='') 
    {
        $proc=proc_open($cmd, array(
            0 => array('pipe', 'r'), 
            1 => array('pipe', 'w'), 
            2 => array('pipe', 'w')), $pipes); 

        if( $input )
            fwrite($pipes[0], $input);

        fclose($pipes[0]); 
        $stdout = stream_get_contents($pipes[1]);fclose($pipes[1]); 
        $stderr = stream_get_contents($pipes[2]);fclose($pipes[2]); 
        $rtn = proc_close($proc); 
        return (object) array(
            'stdout' => $stdout,
            'stderr' => $stderr,
            'return' => $rtn
        ); 
    } 
}

?>
