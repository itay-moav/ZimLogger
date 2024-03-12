<?php namespace ZimLogger\Handlers;
/**
 * Not a real logger -> this is a sink
 * 
 * @author itaymoav
 */
class Nan extends aLogHandler{
    	
    /**
     * 
     * {@inheritDoc}
     * @see \ZimLogger\Handlers\aLogHandler::log()
     */
    protected function log(string $inp,int $severity,array $full_stack_data = []):void{
        //abba nagila
	}
}