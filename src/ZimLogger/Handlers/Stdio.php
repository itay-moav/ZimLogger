<?php namespace ZimLogger\Handlers;
class Stdio extends aLogHandler{
    
    /**
     * 
     * {@inheritDoc}
     * @see \ZimLogger\Handlers\aLogHandler::log()
     */
    protected function log(string $inp,int $severity,array $full_stack_data = []):void{
		echo $inp . "\n";
		if($full_stack_data){
		    echo "=============================== FULL STACK ======================================\n";
		    print_r($full_stack_data);
		}
		
	}
}