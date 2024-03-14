<?php namespace ZimLogger;

/**
 * Logger Factory and manager, handles creating logs for systems and holding API for the current log.
 */
abstract class MainZim{
    
    /**
     * Includes the shortcuts file, assuming the autoloader can fins this file
     */
    static public function include_shortcuts():void{
        require __DIR__ . DIRECTORY_SEPARATOR . 'shortcuts.php';
    }
    
	/**
	 * @var \ZimLogger\Handlers\aLogHandler global/default Logger to be used in the app.
	 * 
	 *             To change global Logger, simply use the factory again (or just instantiate 
	 *             the logger you want).
	 */
    
    //ACTIVATE static public \ZimLogger\Handlers\aLogHandler $GlobalLogger;
	
	static public $GlobalLogger;
    static public $CurrentLogger;
	
    /**
	 * Sets the global logger as a static member in MainZim, it is the one who will be accessible from the dbg() functions 
	 * 
	 * @param string $log_name		A name for the log output (for example, if this is a file log, this would be part of the file name, usage
	 *								depends on the specific Logger class used.
	 * @param string $logger_classname	Logger type, depends on the class names u have under the Logger folder. Use the Logger_[USE_THIS] value
	 * 								as the available types.
	 * @param integer $verbosity_level which type of messages do I actually log, Values are to use the constants Logger::VERBOSITY_LVL_*
	 *								Sadly, in your environment file, you will probably need to use pure numbers, unless u include the Logger.php 
	 *								before you load the environment values (where you should configure the system verbosity level).
	 * @param string $endpoint     	The consumer where we write the log into, Can be a folder, db, rest server etc. If the resource you try 
	 *                              to write into requires a more complex data structure to connect (like username + password+port) Use
	 *                              DSN like formatted string to pass these values as one string
	 * 
	 * @param bool $use_low_memory_footprint This flag will prevent from a full dump of an object, as there might be huge objects which can cause out of memory errors.
	 *                                       Flag can also be used differently in each concrete logger 
	 *
	 * @return \ZimLogger\Handlers\aLogHandler
	 */
    static public function setGlobalLogger(string $log_name,string $logger_classname,int $verbosity_level,string $endpoint='',bool $use_low_memory_footprint=false):\ZimLogger\Handlers\aLogHandler{
	    return self::$GlobalLogger = self::factory($log_name,$logger_classname,$verbosity_level,$endpoint,$use_low_memory_footprint);
	}
	
	/**
	 * TOBEDELETED
	 * @param string $log_name
	 * @param string $logger_classname
	 * @param int $verbosity_level
	 * @param string $endpoint
	 * @param bool $use_low_memory_footprint
	 * @return \ZimLogger\Streams\aLogStream
	 */
	static public function setCurrentLogger(string $log_name,string $logger_classname,int $verbosity_level,string $endpoint='',bool $use_low_memory_footprint=false):\ZimLogger\Streams\aLogStream{
	    return self::$GlobalLogger = self::$CurrentLogger = self::old_factory($log_name,$logger_classname,$verbosity_level,$endpoint,$use_low_memory_footprint);
	}
	
	/**
	 * Creates a logger
	 * 
	 * @param string $log_name		A name for the log output (for example, if this is a file log, this would be part of the file name, usage
	 *								depends on the specific Logger class used.
	 * @param string $logger_classname	Logger type, depends on the class names u have under the Logger folder. Use the Logger_[USE_THIS] value
	 * 								as the available types.
	 * @param integer $verbosity_level which type of messages do I actually log, Values are to use the constants Logger::VERBOSITY_LVL_*
	 *								Sadly, in your environment file, you will probably need to use pure numbers, unless u include the Logger.php
	 *								before you load the environment values (where you should configure the system verbosity level).
	 * @param string $endpoint     	The consumer where we write the log into, Can be a folder, db, rest server etc. If the resource you try 
	 *                              to write into requires a more complex data structure to connect (like username + password+port) Use
	 *                              DSN like formatted string to pass these values as one string
	 * @param bool $use_low_memory_footprint This flag will prevent from a full dump of an object, as there might be huge objects which can cause out of memory errors.
	 *                                       Flag can also be used differently in each concrete logger
	 *
	 * @return \ZimLogger\Handlers\aLogHandler
	 */
	static public function factory(string $log_name,string $logger_classname,int $verbosity_level,string $endpoint='',bool $use_low_memory_footprint=false):\ZimLogger\Handlers\aLogHandler{
	    $class_name = '';
	    if(strpos($logger_classname, '_')){
	        $class_name = '\\' . $logger_classname;  
	    } elseif(strpos($logger_classname, '\\') !== false){
	        $class_name = $logger_classname;
	    } else {
	        $class_name = '\ZimLogger\Handlers\\' . ucfirst($logger_classname);
	    }
	    return new $class_name($log_name,$verbosity_level,$endpoint,$use_low_memory_footprint);
	}
	
	
	/**
	 * TOBEDELETED
	 * @param string $log_name
	 * @param string $logger_classname
	 * @param int $verbosity_level
	 * @param string $endpoint
	 * @param bool $use_low_memory_footprint
	 * @return \ZimLogger\Streams\aLogStream
	 */
	static public function old_factory(string $log_name,string $logger_classname,int $verbosity_level,string $endpoint='',bool $use_low_memory_footprint=false):\ZimLogger\Streams\aLogStream{
	    $class_name = '';
	    if(strpos($logger_classname, '_')){
	        $class_name = '\\' . $logger_classname;
	    } elseif(strpos($logger_classname, '\\') !== false){
	        $class_name = $logger_classname;
	    } else {
	        $class_name = '\ZimLogger\Streams\\' . ucfirst($logger_classname);
	    }
	    return new $class_name($log_name,$verbosity_level,$endpoint,$use_low_memory_footprint);
	}
	
	/**
	 * 
	 * @param string $log_name
	 * @param string $logger_full_classname
	 * @param int $verbosity_level
	 * @param string $endpoint     	The consumer where we write the log into, Can be a folder, db, rest server etc. If the resource you try 
	 *                              to write into requires a more complex data structure to connect (like username + password+port) Use
	 *                              DSN like formatted string to pass these values as one string
	 * @param bool $use_low_memory_footprint
	 * @return \ZimLogger\Handlers\aLogHandler
	 */
	static public function factory2(string $log_name,string $logger_full_classname,int $verbosity_level,string $endpoint='',bool $use_low_memory_footprint=false):\ZimLogger\Handlers\aLogHandler{
	    return new $logger_full_classname($log_name,$verbosity_level,$endpoint,$use_low_memory_footprint);
	}
	
	/**
	 * Switches the current logger to use a low memory foot print.
	 * until this becomes an issue, this value will not pass on if current logger is switched inside the same session.
	 */
	static public function currentLoggerUseLowMemoryFootprint():void{
		self::$GlobalLogger->setUseLowMemoryFootprint(true);
	}
	
	/**
	 * Subscribe callable datasource to the default logger.
	 * 
	 * @param callable $func
	 * @param string $label
	 */
	static public function full_stack_subscribe_to_default(callable $func,string $label):void{
	    self::$GlobalLogger->full_stack_subscribe($func,$label);
	}
}
