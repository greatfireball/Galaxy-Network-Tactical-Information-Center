<?php
class NativeSessions implements iSessionHandler {
	public function init() {
		session_start ();
	}
	public function get($key = null) {
		return $_SESSION [$key];
	}
	
	public function set($key = null, $value = null) {
		$_SESSION [$key] = $value;
	}
	
	public function destroy() {
		return session_destroy ();
	}
}