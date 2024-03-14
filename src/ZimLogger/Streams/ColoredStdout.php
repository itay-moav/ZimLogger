<?php namespace ZimLogger\Handlers;
class ColoredStdout extends aLogStream{
   
    /**
     * 
     * @var array<int,string>
     */
    private array $verbosity_to_color_map = [
        self::VERBOSITY_LVL_DEBUG	=> "\033[0;39m",//green
        self::VERBOSITY_LVL_INFO	=> "\033[1;35m",//light purple
        self::VERBOSITY_LVL_WARNING	=> "\033[1;33m",//yellow
        self::VERBOSITY_LVL_ERROR	=> "\033[1;31m",//light red
        self::VERBOSITY_LVL_FATAL   => "\033[0;31m"//red
    ];
    
	/**
	 * {@inheritDoc}
	 * @see \ZimLogger\Streams\aLogStream::log()
	 */
	protected function log(mixed $inp,int $severity,array $full_stack_data = []):void{
	    echo "{$this->verbosity_to_color_map[$severity]}[{$severity}] {$inp}\n","\033[0;39m";
	}
	
}
