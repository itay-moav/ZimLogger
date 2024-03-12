<?php namespace ZimLogger\Handlers;
/**
 * 
 * @author itay
 *
 */
abstract class aLogHandler{
	public const	VERBOSITY_LVL_DEBUG		= 4,
					VERBOSITY_LVL_INFO		= 3,
					VERBOSITY_LVL_WARNING	= 2,
					VERBOSITY_LVL_ERROR		= 1,
					VERBOSITY_LVL_FATAL		= 0
	;

	/**
	 * @var string $log_name Filename in case of the File* loggers
	 */
	protected string $log_name;
	
	/**
	 * 
	 * @var int
	 */
	protected int $verbosity_level;
	
	/**
	 * The consumer where we write the log into, Can be a folder, db, rest server etc. If the resource you try
	 * to write into requires a more complex data structure to connect (like username + password+port) Use
	 * DSN like formatted string to pass these values as one string
	 * 
	 * @var string
	 */
	protected string $endpoint;     	
	
	/**
	 * Whethr to print_r complex $inp or just their type.
	 * These complex vrs can be huge (in the megs)
	 * 
	 * @var boolean
	 */
	protected bool $use_low_memory_footprint = false;
	
	/**
	 * An array of callables. Each callable (by adding it to this array, the logger is subscribing to it)
	 * will be called for log call. 
	 * The use case is u can add a callable from your db client, redis client, solr client to printout current state of the object, for example.
	 * 
	 * @var array<callable>
	 */
	protected array $full_stack_subscribers = [];
	
    /**
     * Used to add some identifier to each log.
     * Usefull when u have different loggers that u want different 
     * messages to be linked
     * 
     * @var string
     */
    protected string $call_sig = '';
	
    /**
     * Used to add some identifier to each log.
     * Usefull when u have different loggers that u want different 
     * messages to be linked
     * 
     * @param string $call_sig
     */
    public function setCallSignature(string $call_sig):void{
		$this->call_sig = $call_sig;
	  }
	  
	/**
	 * @param string $inp
	 * @param int $severity
	 * @param array<mixed> $full_stack_data
	 */
	abstract protected function log(string $inp,int $severity,array $full_stack_data=[]):void;
	
	/**
	 * Translate to string the input, how to output? that depends on how
	 * you implemented the `log` method
	 *
	 * @param mixed $inp
	 * @param int $severity
	 * @param bool $full_stack
	 */
	protected function tlog($inp,int $severity,bool $full_stack=false):void{
	    $text_inp        = '';
	    $full_stack_data = [];
	    
	    //---------------------------------------------------------------
	    
		if ($inp === null){
		    $text_inp = '[NULL input]';
			
		}elseif($inp instanceof \Throwable){
		    $text_inp = $inp . '';//cast to string
			
		}elseif(!is_string($inp) && !is_numeric($inp)){
			if($this->use_low_memory_footprint){
			    $text_inp = match (gettype($inp)){
			        'array'  => 'ARRAY: ' . substr(print_r($inp,true),0,1000),
			        'object' => 'OBJECT ['.get_class($inp).'] ' . substr(print_r($inp,true),0,1000),
			        default  => ' GOT TYPE OF VAR [' . gettype($inp) . ']' . substr(print_r($inp,true),0,1000)
			    };
				
			} else {
			    $text_inp = print_r($inp,true);
			}
			
		} else {
		    $text_inp = $inp.'';
		}
		
		//---------------------------------------------------------------
		
		if($full_stack){
			$full_stack_data['session']   = $_SESSION ?? [];
			$full_stack_data['request']   = $_REQUEST;//it is always set
			$full_stack_data['request']['AND THE RAW BODY IS'] = file_get_contents('php://input');
			$full_stack_data['server']    = $_SERVER;
			$full_stack_data['subscribers']  = $this->get_full_stack_subscribers_data();
		}
		
		//---------------------------------------------------------------
		
		$this->log($text_inp,$severity,$full_stack_data);
	}

	/**
	 * @param string $log_name
	 * @param int $verbosity_level
	 * @param string $endpoint
	 */
	final public function __construct(string $log_name,int $verbosity_level,string $endpoint='',bool $use_low_memory_footprint=false){
		$this->log_name 				= $log_name;
		$this->verbosity_level 			= $verbosity_level;
		$this->endpoint 		    	= $endpoint;
		$this->use_low_memory_footprint = $use_low_memory_footprint;
		$this->init();
	}
	
	/**
	 * A hook to add your code to run right after the constructor is done
	 * but before it returns
	 */
	protected function init():void{
	}
	
	/**
	 * Subscribe external object/function with their own state so it can be added to any log entry, if
	 * wanted
	 * 
	 * For example
	 * 
	 * class db{
	 *    private string $last_query;
	 *    private array  $last_result;
	 *    
	 *    public function send_to_log():array{
	 *     return [
	 *         'last_query'=>$this->last_query(),
	 *         'last_result'=>$this->last_result()
	 *     ];
	 *    }
	 * }
	 * 
	 * //In your bootstrap piece
	 * $myDb = new db();
	 * $logger->full_stack_subscribe(fn()=>$myDb->send_to_log(),'MyDB data');
	 * 
	 * @param callable $func
	 * @param string $label
	 */
	public function full_stack_subscribe(callable $func,string $label):void{
	    $this->full_stack_subscribers[$label] = $func;
	}
	
	/**
	 * Loops on callables and put it into an array
	 * @return array<mixed>
	 */
	protected function get_full_stack_subscribers_data():array{
	    $debug_data = [];
	    foreach($this->full_stack_subscribers as $label=>$subscriber){
	        $debug_data[$label] = print_r($subscriber(),true);
	    }
	    return $debug_data;
	}
	
	/**
	 * 
	 * @param mixed $inp
	 */
	public function debug(mixed $inp):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_DEBUG){
			$this->tlog($inp,self::VERBOSITY_LVL_DEBUG);
		}
	}
	
	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function info(mixed $inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_INFO){
			$this->tlog($inp,self::VERBOSITY_LVL_INFO,$full_stack);
		}
	}
	
	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function warning(mixed $inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_WARNING){
			$this->tlog($inp,self::VERBOSITY_LVL_WARNING,$full_stack);
		}
	}

	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function error(mixed $inp,bool $full_stack):void{
		if($this->verbosity_level >= self::VERBOSITY_LVL_ERROR){
			$this->tlog($inp,self::VERBOSITY_LVL_ERROR,$full_stack);
		}
	}
	
	/**
	 * 
	 * @param mixed $inp
	 * @param bool $full_stack
	 */
	public function fatal(mixed $inp,bool $full_stack):void{
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
