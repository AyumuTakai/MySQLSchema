<?php

/**
 *
 */
class DBDatabase {
	public $name;
	public $tables;
	public $views;
	/**
	 * @param String $sql
	 * @return DBDatabase
	 */
	public static function fromSQL(/*string*/ $sql) {

	}
	/**
	 * @param DBDatabase $database
	 * @return DBDiff
	 */
	public function diff(DBDatabase $database) {

	}
}