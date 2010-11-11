<?php
class ServiceProvider {
	
	private static $config = array ();
	private static $instance = NULL;
	
	private function __construct() {
	}
	
	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = new self ();
		}
		return self::$instance;
	}
	
	private function __clone() {
	}
	
	public function loadConfig($ConfigPath) {
		if (! is_file ( $ConfigPath )) {
			return;
		}
		try {
			$yaml = new sfYamlParser ();
			$this->config = $yaml->parse ( file_get_contents ( $ConfigPath ) );
		} catch ( InvalidArgumentException $e ) {
			echo "Unable to parse the YAML string: " . $e->getMessage ();
		}
	}
	/**
	 * Bessere Version
	 * http://de2.php.net/manual/de/reflectionclass.newinstanceargs.php @ Comments
	 * :)
	 */
	public function getService($service_name) {
		if (empty ( $this->config [$service_name] )) {
			return false;
		}
		if (count ( $this->config [$service_name] ['args'] ) > 1) {
			if (true == $this->config [$service_name] ['is_static']) {
				return call_user_func_array ( array ($this->config [$service_name] ['class'], $this->config [$service_name] ['method'] ), $this->config [$service_name] ['args'] );
			}
			
			if (false === method_exists ( $this->config [$service_name] ['class'], (! empty ( $this->config [$service_name] ['method'] ) ? $this->config [$service_name] ['method'] : '__construct'))) {
				exit ( "Constructor for the class <strong>$service_name</strong> does not exist, you should not pass arguments to the constructor of this class!" );
			}
			$refMethod = new ReflectionMethod ( $this->config [$service_name] ['class'], '__construct' );
			$params = $refMethod->getParameters ();
			$re_args = array ();
			foreach ( $this->config [$service_name] ['args'] as $key => $param ) {
				$re_args [$key] = $this->config [$service_name] ['args'] [$key];
			}
			$refClass = new ReflectionClass ( $this->config [$service_name] ['class'] );
			return $refClass->newInstanceArgs ( ( array ) $re_args );
		} else {
			$refClass = new ReflectionClass ( $this->config [$service_name] ['class'] );
			return $refClass->newInstanceArgs ();
		}
	}
}