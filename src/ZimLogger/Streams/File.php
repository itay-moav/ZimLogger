<?php namespace ZimLogger\Streams;
class File extends aLogStream{
    
    /**
     * {@inheritDoc}
     * @see \ZimLogger\Streams\aLogStream::init()
     */
    protected function init():void{
        $this->log_name = $this->endpoint . $this->log_name . @date('m_d_Y', time()).'.log';
	}
	
	/**
	 * @throws \Exception
	 * 
	 * {@inheritDoc}
	 * @see \ZimLogger\Streams\aLogStream::log()
	 */
	protected function log(string $inp,int $severity,array $full_stack_data = []):void{
		$stream = fopen($this->log_name, 'a');
		if(!$stream){
		    throw new \Exception("Could not open [{$this->log_name}]");
		}
		fwrite($stream, "[{$severity}][".@date('h:i:s', time())."] [{$this->call_sig}] ".$inp.PHP_EOL);
		if($full_stack_data){
		    fwrite($stream, "[FULL STACK] \n" . print_r($full_stack_data,true) . PHP_EOL);
		}
		fclose($stream);
	}
}
