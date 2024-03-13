<?php
/**
 * 
 * @param mixed $var
 */
function dbg(mixed $var):void{
    \ZimLogger\MainZim::$GlobalLogger->debug($var);
}

/**
 * 
 * @param mixed $var
 */
function dbgd(mixed $var):void{
    dbg($var);
    die;
}

/**
 * @param string|int $n
 */
function dbgn(string|int $n):void{
    dbg('===================' . $n . '===================');
}

/**
 * @param string|int $n
 */
function dbgnd(string|int $n):never{
    dbgn($n);
    die;
}

/**
 * @param string|int $n
 * @param mixed $var
 */
function dbgr(string|int $n,mixed $var):void{
    dbgn($n);
    dbg($var);
}

/**
 * @param string $n
 * @param mixed $var
 */
function dbgrd(string|int $n,mixed $var):never{
    dbgr($n,$var);
    die;
}

/**
 * @param mixed $inp
 */
function debug(mixed $inp):void {
    \ZimLogger\MainZim::$GlobalLogger->debug($inp);
}

/**
 * @param mixed $inp
 * @param bool $full_stack
 */
function info(mixed $inp,bool $full_stack=false):void {
    \ZimLogger\MainZim::$GlobalLogger->info($inp, $full_stack);
}

/**
 * @param mixed $inp
 * @param bool $full_stack
 */
function warning(mixed $inp,bool $full_stack=false):void {
    \ZimLogger\MainZim::$GlobalLogger->warning($inp, $full_stack);
}

/**
 * @param mixed $inp
 * @param bool $full_stack
 */
function error(mixed $inp,bool $full_stack=false):void{
    \ZimLogger\MainZim::$GlobalLogger->error($inp, $full_stack);
}

/**
 * @param mixed $inp
 * @param bool $full_stack
 */
function fatal(mixed $inp,bool $full_stack=false):void{
    \ZimLogger\MainZim::$GlobalLogger->fatal($inp, $full_stack);
}
