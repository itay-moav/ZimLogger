<?php namespace ZimLogger\Streams;
/**
 * Standard error 
 */
class StdError extends aLogStream{
    
    /**
	 * @throws \Exception
	 * 
	 * {@inheritDoc}
	 * @see \ZimLogger\Streams\aLogStream::log()
	 */
	protected function log(string $inp,int $severity,array $full_stack_data = []):void{
        file_put_contents( 'php://stderr',"[{$severity}][".@date('h:i:s', time())."] [{$this->call_sig}] " );
		if($full_stack_data){
		    file_put_contents( 'php://stderr', "[FULL STACK] \n" . print_r($full_stack_data,true));
		}
	}
}
