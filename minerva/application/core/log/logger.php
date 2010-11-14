<?php
namespace \Athene\Core\Logging;

class Logger
{
	private $foo;
	private $bar;
	private $bla;
	public function __construct($foo = null, $bar = null, $bla = null){
		$this->foo = $foo;
		$this->bar = $bar;
		$this->bla = $bla;
	}
}