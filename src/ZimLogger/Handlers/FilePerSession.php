<?php namespace ZimLogger\Handlers;
class FilePerSession extends File{
    
    /**
     * {@inheritDoc}
     * @see \ZimLogger\Handlers\aLogHandler::init()
     */
    protected function init():void{
        $this->log_name = $this->endpoint . session_id() . '-' . $this->log_name . @date('m_d_Y', time()).'.log';
	}
}
