<?php namespace ZimLogger\Handlers;
class File extends aLogHandler{
    
    /**
     * {@inheritDoc}
     * @see \ZimLogger\Handlers\aLogHandler::init()
     */
    protected function init():void{
        $this->log_name = $this->endpoint . $this->log_name . @date('m_d_Y', time()).'.log';
	}
	
	/**
	 * @throws \Exception
	 * 
	 * {@inheritDoc}
	 * @see \ZimLogger\Handlers\aLogHandler::log()
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
