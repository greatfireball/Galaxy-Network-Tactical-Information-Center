<?php
interface iSessionHandler {
	public function init();
	public function get($key = null);
	public function set($key = null, $value = null);
	public function destroy();
}