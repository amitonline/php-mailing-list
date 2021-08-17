<?php

/**
 * Class to handle CRUD operations for a mysql table
 * Written by Amit Sengupta, Truelogic.org , Feb 2015, Hyderabad
**/
class Table {
	
	private $mDb = null;		// the database class
	private $mTableName = "";			// table name
	private $mError = "";


	/**
	 * Constructor
	 * @param object $db Database object alrady created before being passed
	 *
	 */
	public function __construct($db, $name) {
		$this->mDb = $db;
		$this->mTableName = $name;
	}


	/** 
	 * Get Db instance
	 **/
	function getDB() {
		return $this->mDb;
	}

	/**
	 * Get last error message
	 * 
	 * @return string $mError
	 */
	public function getLastError() {
		return $this->mError;	
	}

	/**
	 * Do an INSERT
	 * @param array $fields array of fields and values
	 * @return int $retval - nonzero value if ok else zero
	 */
	public function insert($fields) {
		$retVal = 0;
		$this->mError = "";

		$sql = "insert into " . $this->mTableName ;

		// get fieldnames
		$fieldNames = "(";
		foreach($fields as $field=>$value) {
			$fieldNames .= $field .",";
		}
		// remove trailing comma
		$fieldNames = substr($fieldNames, 0, strlen($fieldNames)-1);
		$fieldNames .= ")";

		// put value placeholders
		$values = "(";
		foreach($fields as $field=>$value) {
			$values .= ":" . $field .",";
		}
		// remove trailing comma
		$values = substr($values, 0, strlen($values)-1);
		$values .= ")";
		
	
		$sql .= " " . $fieldNames . " values " . $values;
		$this->mDb->query($sql);
		
		    
		foreach($fields as $field=>$value) {
			if (!$this->mDb->bind(":" . $field, $value)) {
			    $retval = 0;
			    $this->mError = "Binding failed for " . $field . " =>" . $value;
			    return $retval;
			}
		}
		$this->mDb->execute();
		$this->mError = $this->mDb->getLastError();
		$retVal = $this->mDb->lastInsertId();


		return $retVal;
	}


	/**
	 * Do a DELETE
	 * @param string $expr delete expression
	 * @return bool $retval - true if ok else zero
	 */
	public function delete($expr) {
		$retVal = true;
		$this->mError = "";


		$sql = "delete from " . $this->mTableName . " where " . $expr;
		$this->mDb->query($sql);
		$retVal = $this->mDb->execute();
		$this->mError = $this->mDb->getLastError();

		return $retVal;
	}

	/**
	 * Do an UPDATE
	 * @param array $fields array of fields and values
	 * @param string $expr update expression
	 * @return int $retval - nonzero value if ok else zero
	 */
	public function update($fields, $expr) {
		$retVal = 0;
		$this->mError = "";

		$sql = "update " . $this->mTableName ;
		// get fieldnames
		$fieldNames = "set ";
		foreach($fields as $field=>$value) {
			$fieldNames .= $field ."=:" . $field. ",";
		}
		// remove trailing comma
		$fieldNames = substr($fieldNames, 0, strlen($fieldNames)-1);

		$sql .= " " . $fieldNames . " where " . $expr;
		$this->mDb->query($sql);
		foreach($fields as $field=>$value) {
			$this->mDb->bind(":" . $field, $value);
		}
		
		$this->mDb->execute();
		$this->mError = $this->mDb->getLastError();
		$retVal = $this->mDb->lastInsertId();
		return $retVal;
	}
	
	/**
	 * Fetch a single row matching expr
	 * @param array $fields, list of fields to fetch, if null then all fields
	 * @param array $expr array of fetch expressions
	 * @param array $oper array of operators for each expression 
	 * @param bool $useOR - if false then use and for multiple expressions else OR
	 * @return array $retVal - empty if error else array of row
	 */
	public function fetch($fields, $expr, $oper, $useOR) {
		$retVal = array();
		$this->mError = "";
		$fldString = "*";
		if (is_array($fields)) {
			$fldString = "";
			foreach($fields as $f) {
				$fldString .= $f . ",";
			}
			// remove trailing slash
			if ($fldString != "")
				$fldString = substr($fldString, 0, strlen($fldString)-1);
		}

		$sql = " select " . $fldString . " from " . $this->mTableName . " where ";
		$filter = "";
		$operElem = 0;
		foreach($expr as $fld=>$value) {
			if ($filter != "") {
			    if ($useOR)
				$filter .= " or ";
			    else	
				$filter .= " and " ;
			}				
			$filter .= $fld . $oper[$operElem] . ":" . $fld;
			$operElem ++;
		}
		$sql .= $filter . " limit 0,1";
		$this->mDb->query($sql);
		foreach($expr as $fld=>$value) {
			$this->mDb->bind(":" . $fld , $value);
		}
		$retVal = $this->mDb->single();
		$this->mError = $this->mDb->getLastError();
		return $retVal;
	}


	/**
	 * Fetch count of rows for an expression
	 * @param array $expr array of fetch expressions
	 * @param array $oper array of operators for each expression 
	 * @return int $retVal - count of rows matched
	 */
	public function fetchCount($expr, $oper) {
		$retVal = 0;
		$this->mError = "";

		$fldString = "count(*) as total";
		
		$sql = " select " . $fldString . " from " . $this->mTableName . " where ";		
		$filter = "";
		$operElem = 0;
		foreach($expr as $fld=>$value) {
			if ($filter != "")
				$filter .= " and " ;
			$filter .= $fld . $oper[$operElem] . ":" . $fld;
			$operElem ++;
		}
		
		$sql .= $filter;
		if (!is_null($limit_start) && !is_null($limit_count))
			$sql .= " limit " . $limit_start ."," . $limit_count;
		$this->mDb->query($sql);
		
		foreach($expr as $fld=>$value) {
			$this->mDb->bind(":" . $fld , $value);
		}


		$rows = $this->mDb->single();
		if (is_array($rows)) {
			$retVal = $rows["total"];
		}
		$this->mError = $this->mDb->getLastError();
		return $retVal;
	}

	/**
	 * Fetch a multiple rows matching expr
	 * @param array $fields, list of fields to fetch, if null then all fields
	 * @param array $expr array of fetch expressions
	 * @param array $oper array of operators for each expression 
	 * @param int $limit_start - null if no limit start value else number
	 * @param int @limit_count - null if no limit count value else number
	 * @param string @sort - sort expression
	 * @return array $retVal - empty if error else array of rows
	 */
	public function fetchAll($fields, $expr, $oper, $limit_start=null, $limit_count=null, $sort=null) {
		$retVal = array();
		$this->mError = "";

		$fldString = "*";
		if (is_array($fields)) {
			$fldString = "";
			foreach($fields as $f) {
				$fldString .= $f . ",";
			}
			// remove trailing slash
			if ($fldString != "")
				$fldString = substr($fldString, 0, strlen($fldString)-1);
		}

		$sql = " select " . $fldString . " from " . $this->mTableName . " where ";		
		$filter = "";
		$operElem = 0;
		foreach($expr as $fld=>$value) {
			if ($filter != "")
				$filter .= " and " ;
			$filter .= $fld . $oper[$operElem] . ":" . $fld;
			$operElem ++;
		}
		
		$sql .= $filter;
		if ($sort != null && $sort != "")
			$sql .= " order by " . $sort;
		if (!is_null($limit_start) && !is_null($limit_count))
			$sql .= " limit " . $limit_start ."," . $limit_count;
		$this->mDb->query($sql);
		foreach($expr as $fld=>$value) {
			$this->mDb->bind(":" . $fld , $value);
		}
		$retVal = $this->mDb->resultset();
		$this->mError = $this->mDb->getLastError();
		
		return $retVal;
	}

	/**
	 * Get column metadata of a table
	 * @param string $tableName name of table
	 * @return array $columns - array of column details
	 */
	public function getColumns() {
		$retVal = array();
		$this->mError = "";

		$sql = "select  column_name, column_type, character_maximum_length from information_schema.columns where " . 
				" lower(table_name) = :tablename";
		$this->mDb->query($sql);
		$this->mError = $this->mDb->getLastError();
		$this->mDb->bind(":tablename", strtolower($this->mTableName));
		$rows = $this->mDb->resultset();
		if (is_array($rows)) {
			foreach($rows as $row) {
				$fName = $row["column_name"];
				$fType = $row["column_type"];
				$fLength= $row["character_maximum_length"];

				$single = array();
				$single["name"] = $fName;
				$single["type"] = $fType;
				$single["length"] = $fLength;
				$retVal[] = $single;
			}
		}
		return $retVal;
	}
	
}

?>
