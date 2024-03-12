<?php namespace ZimLogger\Handlers;
/**
 * RELAX!!! STD means STandarD error 
 */
class StdError extends aLogHandler{
    
    /**
	 * @throws \Exception
	 * 
	 * {@inheritDoc}
	 * @see \ZimLogger\Handlers\aLogHandler::log()
	 */
    protected function log(string $inp,int $severity,array $full_stack_data = []):void{
        
        $log = "[{$severity}][".@date('h:i:s', time()) . ']' . ($this->call_sig ? "[{$this->call_sig}]" : '') . $inp;
        file_put_contents( 'php://stderr',$log . PHP_EOL );
        if($full_stack_data){
                file_put_contents( 'php://stderr', '[FULL STACK]' . PHP_EOL . print_r($full_stack_data,true) . PHP_EOL);
        } // EOF if full stack
    }//EOF log()
}
