<?php namespace ZimLogger\Streams;
abstract class aLogStream{
	public const	VERBOSITY_LVL_DEBUG		= 4,
					VERBOSITY_LVL_INFO		= 3,
					VERBOSITY_LVL_WARNING	= 2,
					VERBOSITY_LVL_ERROR		= 1,
					VERBOSITY_LVL_FATAL		= 0
	;
	
	protected $log_name 	   			= '', 
			  $verbosity_level 			= null, 
	          $target_stream 			= null,
	          $use_low_memory_footprint = false
	;
	
	/**
	 * This function writes to the designated output stream/resources.
	 */
	abstract protected function log($txt,$severity,$full_stack_data=null);
	
	/**
	 * Translate to string the input, how to output? that depends on how
	 * you implemented the `log` method
	 *
	 * @param mixed $inp
	 * @param int $severity
	 * @param string $full_stack
	 */
	protected function tlog($inp,int $severity,bool $full_stack=false){
		if ($inp === null){
			$inp = 'NULL';
			
		}elseif($inp instanceof \Throwable){
			//do nothing
			
		}elseif(!is_string($inp) && !is_numeric($inp)){
			if($this->use_low_memory_footprint){
				switch (gettype($inp)){
					case 'array':
						$inp = print_r($inp,true);
						break;
						
					case 'object':
						$inp = get_class($inp);
						break;
						
					default:
						$inp = ' GOT TYPE OF VAR ' . gettype($inp);
						break;
				}
			} else {
				$inp = print_r($inp,true);
			}
		}
		
		$full_stack_data = null;
		if($full_stack){
			$full_stack_data['session'] = isset($_SESSION)?$_SESSION:[];
			$full_stack_data['request'] = (isset($_REQUEST)?$_REQUEST:[]);
			$full_stack_data['request'][]= ' ANDTHERAWBODYIS ' . file_get_contents('php://input');
			$full_stack_data['server']  = isset($_SERVER)?$_SERVER:[];
			$full_stack_data['database'] = print_r(\Talis\Services\Sql\Factory::getDebugInfo(),true);
		}
		$this->log($inp,$severity,$full_stack_data);
	}

	/**
	 * @param string $log_name
	 * @param int $verbosity_level
	 * @param string $target_stream
	 */
	final public function __construct($log_name,$verbosity_level,$target_stream=null,bool $use_low_memory_footprint=false){
		$this->log_name 				= $log_name;
		$this->verbosity_level 			= $verbosity_level;
		$this->target_stream 			= $target_stream;
		$this->use_low_memory_footprint = $use_low_memory_footprint;
		$this->init();
	}
	
	/**
	 * 
	 * @return \ZimLogger\Streams\aLogStream
	 */
	protected function init():\ZimLogger\Streams\aLogStream{
		return $this;
	}
	
	/**
	 * 
	 * @param mixed $inp
	 */
	public function debug($inp):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_DEBUG){
			$this->tlog($inp,self::VERBOSITY_LVL_DEBUG);
		}
	}
	
	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function info($inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_INFO){
			$this->tlog($inp,self::VERBOSITY_LVL_INFO,$full_stack);
		}
	}
	
	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function warning($inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_WARNING){
			$this->tlog($inp,self::VERBOSITY_LVL_WARNING,$full_stack);
		}
	}

	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function error($inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_ERROR){
			$this->tlog($inp,self::VERBOSITY_LVL_ERROR,$full_stack);
		}
	}
	
	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function fatal($inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_FATAL){
			$this->tlog($inp,self::VERBOSITY_LVL_FATAL,$full_stack);
		}
	}
	
	/**
	 * This will prevent some big log dumps on crashes.
	 * Turn this on only after memory issues are found.
	 * 
	 * @param bool $use_low_memory_footprint
	 */
	final public function setUseLowMemoryFootprint(bool $use_low_memory_footprint):void{
		$this->use_low_memory_footprint = $use_low_memory_footprint;
	}
}
