<?php

/**
 *
 */
class DBTable {
	public $name;
	public $columns;
	public $indexes;
	/**
	 * @param String $sql
	 * @return DBTable
	 */
	public static function fromSQL(/*string*/ $sql) {
		if($sql) {
			preg_match('/CREATE\s+TABLE\s+(\w+)\s*\((.*)\)/i',$sql,$match);
			$table = new static();
			$table->name = $match[1];
			$create_definitions = explode(',',$match[2]);
			foreach($create_definitions as $definition) {
				$column = DBColumn::fromSQL($definition);
				$table->columns[$column->name] = $column;
			}
			return $table;
		}else{
			return null;
		}
	}
	/**
	 * @return String sql strings.
	 */
	public function __toString(){
		$create_definitions = array();
		foreach ($this->columns as $column) {
			$create_definitions[$column->name] = (string)$column;
		}
		$create_definitions = implode(',',$create_definitions);
		$str = "CREATE TABLE {$this->name} ({$create_definitions})";
		return $str;
	}
	/**
	 * @return String sql strings.
	 */
	public function toSQL(){
		return $this->__toString();
	}

	/**
	 * @param DBDatabase $database
	 * @return DBDiff
	*/
	public function diff(DBTable $table) {
		$alter_specifications = array();
		if($this->name !== $table->name) {
			$alter_specifications[] =  "RENAME {$table->name}";
		}

		// ADD or CHANGE
		foreach ($table->columns as $column) {
			if(isset($this->columns[$column->name])){
				if($this->columns[$column->name] != $column){
					$alter_specifications[] = "CHANGE {$column->name} {$column}";
				}
			}else{
				$alter_specifications[] = "ADD {$column}";
			}
		}

		// DROP
		$dropColmuns = array_diff(array_keys($this->columns),array_keys($table->columns));
		foreach($dropColmuns as $columnName) {
			$alter_specifications[] = "DROP {$columnName}";
		}

		if($alter_specifications){
			$alter_specifications = implode(',',$alter_specifications);
			$diff = "ALTER TABLE {$this->name} {$alter_specifications}";
			return $diff;
		}
		return '';
	}
}