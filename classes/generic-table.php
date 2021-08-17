<?php

require_once("PDOLib/Database.php");
require_once("PDOLib/Table.php");
class GenericTable {

	var $mDocRoot = "";
	var $mError = "";
	var $mDb = null;
	var $mTable = "";	// table name to be filled in subclasses

	/**
	 * Constructoor
	 * @param string $docRoot - document root
	 * @param string $server - db server
	 * @param string $userid - user id
	 * @param string $pwd - password
	 * @param string $db - database name
	 */
	function __construct($docRoot, $server, $userid, $pwd, $db) {
		$this->mDocRoot = $docRoot;

		try {
			$this->mDb = new Database($server, $db, $userid, $pwd); 
			$this->mError = $this->mDb->getLastError();
		} catch (Exception $ex) {
			$this->mError = $ex->getMessage();
		}
	}




	/**
	 * Return a list of heads for filling a dropdown box
	 * @param int selectedId  item to be selected
	 * @param string  $sortField field to sort list on
	 * @param string $valueField field to get values from
	 * @param string $labelField field to get labels from
	 * @return string $returnString string of item values
	 */
	function getListForDropDown($selectedId, $sortField, $valueField, $labelField) {
		$returnString = "";

		$table = new Table($this->mDb, $this->mTable);
		$rows = $table->fetchAll(array($valueField, $labelField), array("ID"=>0), array(">"), null, null, $sortField);
		foreach($rows as $item) {
			$selected = "";
			if (($selectedId > 0 || $selectedId != "") && $item[$valueField] == $selectedId )
				$selected = " selected ";

			$returnString .= "<option value=\"" . $item[$valueField] . "\"" . $selected . ">" . 
				$item[$labelField] . "</option>";
		}
		
		return $returnString;
	}
	


	/**
	 * Return a single row using an id
	 * @param string $idField id field 
	 * @param int $idValue  row id value
	 * @return array $row 
	 */
	function getRowById($idField, $idValue) {
		$table = new Table($this->mDb, $this->mTable);
		$row = $table->fetch(null, array($idField=>$idValue), array("="), null, null, null);
		return $row;
	}
	


	/**
	 * Update/Add  entry (assumes that the primary key is ID
	 * @param array $arrData array of field/values
	 * @param int $id entry id - zero if new entry 
	 * @return int $newId > 0 if inserted else null
	 */
	function update($arrData, $id) {
		
		$this->mError = null;
		$table = new Table($this->mDb, $this->mTable);
		if ($id == 0)
			$newId = $table->insert($arrData);
		else
			$table->update($arrData, "id=" . $id); 

		$this->mError = $this->mDb->getLastError();
		return $newId;
	}


	/**
	 * Update  entry using sql expression
	 * @param array $arrData array of field/values
	 * @return int $newId > 0 if inserted else null
	 */
	function updateByExpression($arrData, $expr) {
		
		$this->mError = null;
		$table = new Table($this->mDb, $this->mTable);
		$table->update($arrData, $expr); 

		$this->mError = $this->mDb->getLastError();
		return $newId;
	}



	/** 
	 * Delete row by rimary key (assumes that primary key is ID)
	 * @param int $id payment id
	 * @param int $id id
	 i* @return nil
	 */
	function delete($id) {
	
		$this->mError = null;
		$table = new Table($this->mDb, $this->mTable);
		$table->delete("ID=" . $id);
		
		$this->mError = $this->mDb->getLastError();
		return $newId;

	}

	/** 
	 * Delete row by expression
	 * @param string $expr sql expression
	 i* @return nil
	 */
	function deleteByExpression($expr) {
	
		$this->mError = null;
		$table = new Table($this->mDb, $this->mTable);
		$table->delete($expr);
		
		$this->mError = $this->mDb->getLastError();
		return $newId;

	}



	/**
	 * Default desctructor
	 *
	 */
	function __destruct() {
	
		$this->mDb = null;
	}

}


?>
