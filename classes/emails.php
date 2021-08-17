<?php
require_once($g_docRoot . "classes/generic-table.php");
class Emails extends GenericTable {

	var $mTable = "emails";


	/**
	 * Get count of all rows 
	 * @param string $emailid
	 * @return int $retVal count of rows
	 */
	function getCount($emailid) {
		$retVal = 0;
		
		$sql ="SELECT count(*) as total from " . $this->mTable . " where ID > 0 ";
	 	if ($emailid != null && $emailid != "")
			$sql .= " and email like :emailid";
		$this->mDb->query($sql);
		if ($emailid != null && $emailid != "")
		   $this->mDb->bind(":emailid", "%" . $emailid . "%");

		$rows = $this->mDb->single();
		
		if (is_array($rows)) {
			$retVal = $rows["total"];
		}
		
		$this->mError = $this->mDb->getLastError();
		return $retVal;

	}


	/**
	 * Get  list 
	 * @param string $emailid
	 * @param int $row - starting row
	 * @param int $rowsPerPage - rows to fetch
	 * @return array $rows row of data
	 */
	function getList($emailid, $startFrom, $rowsPerPage) {
	
		$sql ="SELECT * from " . $this->mTable . " where ID > 0";
		if ($emailid != null && $emailid != "")
			$sql .= " and email like :emailid";

		$sql .= " order by email ";

		$sql .= " limit " . $startFrom . "," . $rowsPerPage;
		$this->mDb->query($sql);

		if ($emailid != null && $emailid != "")
			$this->mDb->bind(":emailid", "%" . $emailid . "%");


		$rows = $this->mDb->resultset();
		
		$this->mError = $this->mDb->getLastError();
		

		return $rows;

	}


	/**
	 * Get row by email id
	 * @param string $emailId
	 * @return array $row
	 */
	function getRowByEmailId($emailId) {
		$retVal = 0;
		
		$sql ="SELECT * from " . $this->mTable . " where email=:emailid";
		$this->mDb->query($sql);
		$this->mDb->bind(":emailid", $emailId);

		$row = $this->mDb->single();

		
		$this->mError = $this->mDb->getLastError();
		return $row;

	}

	/**
	 * Get row by verification code
	 * @param string $code
	 * @return array $row
	 */
	function getRowByVerifyCode($code) {
		$retVal = 0;
		$sql ="SELECT * from " . $this->mTable . " where vkey=:verify_code";
		$this->mDb->query($sql);
		$this->mDb->bind(":verify_code", $code);

		$row = $this->mDb->single();
		
		$this->mError = $this->mDb->getLastError();
		return $row;

	}
	

}
?>
