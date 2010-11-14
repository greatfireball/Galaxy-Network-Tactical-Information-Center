<?php
namespace Athene\Core\Database;

class Database {
	private $connection = null;
	private $map = array ();
	
	public function __construct($dsn = null, $user = null, $pass = null) {
		$this->connection = new \PDO ( $dsn, $user, $pass );
		$this->generateDatabaseMap ();
	}
	
	public function generateDatabaseMap() {
		$sth = $this->connection->prepare ( "SELECT table_name, table_type, ENGINE FROM information_schema.tables WHERE table_schema =  'minerva' ORDER BY table_name DESC " );
		$sth->execute ();
		$result = $sth->fetchAll ( \PDO::FETCH_CLASS );
		foreach ( $result as $tables ) {
			$sth = $this->connection->prepare ( "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $tables->table_name . "'" );
			$sth->execute ();
			$result = $sth->fetchAll ( \PDO::FETCH_CLASS );
			$this->map [$tables->table_name] = $result;
		}
	}
	
	public function getTableMap($table) {
		if (isset ( $this->map [$table] )) {
			return $this->map [$table];
		} else {
			return false;
		}
	}
	
	public function insert($table, $args) {
	
	}
	
	public function fetch($table, $args) {
	
	}
	
	public function update($table, $args) {
	
	}
	
	public function delete($table, $args) {
	
	}
}