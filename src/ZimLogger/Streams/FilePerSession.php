<?php namespace ZimLogger\Streams;
class FilePerSession extends File{
    
    /**
     * {@inheritDoc}
     * @see \ZimLogger\Streams\aLogStream::init()
     */
    protected function init():void{
        $this->log_name = $this->endpoint . session_id() . '-' . $this->log_name . @date('m_d_Y', time()).'.log';
	}
}
