<?php
class Core {
	private static $AutoloaderScanpaths = array ();
	private static $AutoloaderPathcache = array ();
	
	/**
	 * Autoloader Crap starts here
	 */
	static public function addAutoloaderPath($path) {
		self::$AutoloaderScanpaths [] = $path;
	}
	
	static public function generateAutoloaderConfigFile() {
		if (0 === count ( self::$AutoloaderScanpaths )) {
			// 
		}
		foreach ( self::$AutoloaderScanpaths as $paths ) {
			$path = realpath ( $paths );
			$objects = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( $path ) );
			foreach ( $objects as $name => $object ) {
				if (strstr ( $name, '.php' )) {
					$_tokens = token_get_all ( file_get_contents ( $name ) );
					$_count = count ( $_tokens );
					$i = 0;
					do {
						if ('T_INTERFACE' === token_name ( ( int ) $_tokens [$i] [0] ) || 'T_CLASS' === token_name ( ( int ) $_tokens [$i] [0] )) {
							if ('T_WHITESPACE' === token_name ( ( int ) $_tokens [$i + 1] [0] )) {
								if ('T_STRING' === token_name ( ( int ) $_tokens [$i + 2] [0] )) {
									self::$AutoloaderPathcache [$_tokens [$i + 2] [1]] = $name;
								}
							}
						}
						$i ++;
					} while ( $i < $_count );
				} else {
					continue;
				}
			}
		}
		$file = '<?php $pathcache = array();';
		foreach ( self::$AutoloaderPathcache as $class => $path ) {
			$file .= sprintf ( 'Core::$AutoloaderPathcache[ \'%s\'] = \'%s\';', $class, str_replace ( '\\', '/', $path ) );
		}
		$file .= '?>';
		file_put_contents ( CONFIG_PATH . 'autoloader.php', $file );
	}
	
	static public function loadClass($classname) {
		
		if (0 === count ( self::$AutoloaderPathcache )) {
			require_once (CONFIG_PATH . 'autoloader.php');
		}
		if (stristr ( $classname, '\\' )) {
			$class = explode ( '\\', $classname );
			$class = $class [count ( $class )-1];
		} else {
			$class = $classname;
		}
		
		if (empty ( self::$AutoloaderPathcache [$class] )) {
			return false;
		}
		return require_once (self::$AutoloaderPathcache [$class]);
	}
	
	/** * Autoloader Crap ends here */
	static public function setupRoutes($route_file) {
	}
}