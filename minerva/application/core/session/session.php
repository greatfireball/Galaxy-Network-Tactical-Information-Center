<?php
namespace Athene\Core\Session;

class SessionHandler {
	private $handler = null;
	public function __construct($handler = null) {
		$this->handler = \Athene\Core\Service\ServiceProvider::getInstance ()->getService ( 'SessionHandler' );
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