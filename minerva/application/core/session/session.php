<?php
class SessionHandler {
	private $handler = null;
	public function __construct($handler = null) {
		$this->handler = ServiceProvider::getInstance ()->getService ( 'NativeSessionsHandler' );
		$this->handler->init ();
	}
	
	public function get($key = null) {
		return $this->handler->get ( $key );
	}
	
	public function set($key = null, $value = null) {
		return $this->handler->set ( $key, $value );
	}
	
	public function destroy() {
		$this->handler->destroy ();
	}
}