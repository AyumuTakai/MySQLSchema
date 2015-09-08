<?php

/**
 *
 */
class DBColumn {
	public $name;
	public $type;
	public $length;
	public $primaryKey = false;

	/**
	 * @param DBColumn $column
	 * @param $sql
	 * @return DBColumn
	 */
	protected static function parseDataType(DBColumn $column,$sql) {
		preg_match('/([^()]+)(\((\d+)\))?/',$sql,$matches);
		$column->type = $matches[1];
		if(isset($matches[3])) {
			$column->length = (int)$matches[3];
		}
	}

	/**
	 * @param DBColumn $column
	 * @param array $args
	 * @return DBColumn
	 */
	protected static function parseDefinition(DBColumn $column,array $args){
		static::parseDataType($column,$args[1]);
		for($i = 2;$i < count($args);$i++) {
			switch ( strtoupper($args[$i]) ) {
				case 'PRIMARY':
					if( strtoupper($args[$i+1]) === 'KEY' ) {
						$column->primaryKey = true;
						$i++;
					}
					break;

				default:
					break;
			}
		}
	}

	/**
	 * @return String sql flagment.
	 */
	public function __toString(){
		$str = "{$this->name} {$this->type}";
		if($this->length) {
			$str .= "({$this->length})";
		}
		return $str;
	}

	/**
	 * @param String $sql
	 * @return DBColumn
	 */
	public static function fromSQL(/*string*/ $sql) {
		if($sql){
			$args = explode(' ',$sql);
			$column = new static();
			$column->name = $args[0];
			static::parseDefinition($column,$args);
			return $column;
		}else{
			return null;
		}
	}
	/**
	 * @param DBColumn $column
	 * @return string
	*/
	public function diff(DBColumn $column) {
		if($this == $column){
			return '';
		}else{
			$diff="CHANGE {$this->name} {$column}";
			return $diff;
		}
	}
	/**
	 * @return String sql flagment.
	 */
	public function toSQL(){
		return $this->__toString();
	}
}