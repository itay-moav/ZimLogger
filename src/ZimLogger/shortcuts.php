<?php
function dbg($var):void{
    \ZimLogger\MainZim::$CurrentLogger->debug($var);
}
function dbgd($var):void{
    dbg($var);
    die;
}
function dbgn(string $n):void{
    dbg('===================' . $n . '===================');
}
function dbgnd(string $n):void{
    dbgn($n);
    die;
}
function dbgr(string $n,$var):void{
    dbgn($n);
    dbg($var);
}
function dbgrd(string $n,$var):void{
    dbgr($n,$var);
    die;
}
function debug($inp):void {
    \ZimLogger\MainZim::$CurrentLogger->debug($inp);
}
function info($inp,bool $full_stack=false):void {
    \ZimLogger\MainZim::$CurrentLogger->info($inp, $full_stack);
}
function warning($inp,bool $full_stack=false):void {
    \ZimLogger\MainZim::$CurrentLogger->warning($inp, $full_stack);
}
function error($inp,bool $full_stack=false):void{
    \ZimLogger\MainZim::$CurrentLogger->error($inp, $full_stack);
}
function fatal($inp,bool $full_stack=false):void{
    \ZimLogger\MainZim::$CurrentLogger->fatal($inp, $full_stack);
}
