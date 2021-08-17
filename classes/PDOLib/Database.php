<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);

/**
 * Wrapper class for PHP PDO handling
 * Written by Amit Sengupta, Truelogic.org , Feb 2015, Hyderabad
 * Based on http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
 *
 */

class Database {
	
	private $mHost;
	private $mDBName;
	private $mUser;
	private	$mPwd;

	private $mError;
	private $mDBH;
	private $mStmnt;

	/**
	 * Constructor
	 * @param string $host host uri
	 * @param string $dbname database name
	 * @param string $user user name
	 * @param string $pwd password
	 */
	public function __construct($host, $dbname, $user, $pwd) {
		$this->mHost = $host;
		$this->mDBName = $dbname;
		$this->mUser = $user;
		$this->mPwd = $pwd;

		$dsn = 'mysql:host=' . $this->mHost .';dbname=' . $this->mDBName;
		$options = array(
			PDO::ATTR_PERSISTENT=>true,
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"
		);

		try {
			$this->mDBH = new PDO($dsn, $this->mUser, $this->mPwd, $options);

		} catch (PDOException $pdex) {
			$this->mError = $pdex->getMessage();
		}
	}

	/**
	 * Destructor makes sure connetion is closed
	 */
	public function __destruct() {
		$this->mDBH = null;
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
	 * Execute a Query
	 * @param string $q sql query
	 */
	public function query($q) {
		$this->mStmnt = $this->mDBH->prepare($q);
	}

	/**
 	 * Bind data to query statement
	 * @param string $param name of parameter
	 * @param mixed value value for parameter
	 * @return bool $retVal - true if ok else false
	 */
	public function bind($param, $value) {
		
		switch(true) {
			case is_int($value):
				$type = PDO::PARAM_INT;
				break;
			case is_bool($value):
				$type = PDO::PARAM_BOOL;
				break;
			case is_null($value):
				$type = PDO::PARAM_NULL;
				break;
			default:
				$type = PDO::PARAM_STR;
		}
		$retval = $this->mStmnt->bindValue($param, $value, $type);
		return $retval;
	}


	/**
	 * Execute Statement
	 * @return bool true if ok else false
	 */
	public function execute() {
		try {
			return $this->mStmnt->execute();
		} catch (PDOException $pdex) {
			$this->mError = $pdex->getMessage();
			return false;
		}
	}

	/**
	 * Return resultset from an execute
	 * @return array if error then array with no data else array with data
	 */
	public function resultset() {
		if ($this->execute()) {
			return $this->mStmnt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			$arr = array();
			return $arr;
		}
	}


	/**
	 * Return single record from an execute
	 * @return array if error then array with no data else array with single row of data
	 */
	public function single() {
		if ($this->execute()) {
			return $this->mStmnt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			$arr = array();
			return $arr;
		}
	}


	/**
	 * Get count of affected rows from last operation
	 * @return int no.of rows
	 */
	public function rowCount() {
		if ($this->mStmtnt)
			return $this->mStmnt->rowCount();
		else
			return 0;
	}

	
	/**
	 * Get last inserted id of last inserted row
	 * @return string last inserted id as a string
	 */
	public function lastInsertId() {
		return $this->mDBH->lastInsertId();
	}


	/**
	 * Begin a transaction
	 * @return bool true on success else false
	 **/
	public function beginTxn() {
		return $this->mDBH->beginTransaction();
	}


	/**
	 * Commit a transaction
	 * @return bool true on success else false
	 **/
	public function commitTxn() {
		return $this->mDBH->commit();
	}

	/**
	 * Abort a transaction
	 * @return bool true on success else false
	 **/
	public function cancelTxn() {
		return $this->mDBH->rollback();
	}


	/**
	 * Debug function to dump parameters for current Statement
	 */
	public function debugDumpParams() {
		$this->mStmnt->debugDumpParams();
	} 	


} 
