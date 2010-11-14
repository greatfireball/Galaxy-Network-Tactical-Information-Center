<?php
namespace Athene\Core\Database\ORM;

class Mapper {
	private $db = null;
	private $map = null;
	public function __construct($table = null) {
		$this->db = \Athene\Core\Service\ServiceProvider::getInstance ()->getService ( 'DatabaseProvider' );
		$this->getTable ( $table );
	}
	
	public function getMap(){
		return $this->map;
	}
	
	private function getTable($table = null) {
		$this->map = $this->db->getTableMap ($table);
	}
}