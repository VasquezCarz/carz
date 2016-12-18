<?php
//*********************************************************
//********       Class Database        ********************
//********                             ********************
//******** Author : Matthieu LACOMBLEZ ********************
//******** Last modified : 16 Dec 2016 ********************
//*********************************************************


class Database {
  //-------- Attributes ---------------------------------
  var $host; // host name
  var $name; // database name
  var $user; // user name
  var $pwd;  // password
  var $lnk;  // database link
  //-----------------------------------------------------

  //-------- Constructor --------------------------------
  function Database($name = DB_NAME, $host = DB_HOST, $user = DB_USER, $pwd = DB_PASSWORD) {
    $this->host = $host;
    $this->name = $name;
    $this->user = $user;
    $this->pwd = $pwd;
  }
  //-----------------------------------------------------

  //-------- Set the host name --------------------------
  function setHostName($host) {
    $this->host = $host;
  }
  //-----------------------------------------------------

  //-------- Set the database name ----------------------
  function setDatabaseName($name) {
    $this->name = $name;
  }
  //-----------------------------------------------------

  //-------- Set the user name --------------------------
  function setUserName($user) {
    $this->user = $user;
  }
  //-----------------------------------------------------

  //-------- Set the password ---------------------------
  function setPassword($pwd) {
    $this->pwd = $pwd;
  }
  //-----------------------------------------------------

  //-------- Get the host name --------------------------
  function getHostName() {
    return $this->host;
  }
  //-----------------------------------------------------

  //-------- Get the database name ----------------------
  function getDatabaseName() {
    return $this->name;
  }
  //-----------------------------------------------------

  //-------- Get the user name --------------------------
  function getUserName() {
    return $this->user;
  }
  //-----------------------------------------------------

  //-------- Get the password ---------------------------
  function getPassword() {
    return $this->pwd;
  }
  //-----------------------------------------------------

  //-------- Connection ---------------------------------
  function connect() {
    // On procède à la connexion à la BDD
    $this->lnk = new mysqli($this->host, $this->user, $this->pwd, $this->name);
    
    // On vérifie la connexion
    if ($this->lnk->connect_errno) {
      exit('ERROR : cannot connect to the database ! ('.$this->lnk->connect_errno.') '.$this->lnk->connect_error);
    }
    
    if ($this->lnk->set_charset("utf8")) {
      // on définit l'encodage de caractère en UTF-8
    }
    else {
      exit('ERROR : cannot set charset to UTF-8 !');
    }
	}
  //-----------------------------------------------------

  //-------- Close --------------------------------------
  function close() {
    $this->lnk->close();
  }
  //-----------------------------------------------------

  //-------- Write formatted query ------------------------
  function writeQuery($format) {
    $args = func_get_args();
    unset($args[0]); // get rid of $format
    foreach ($args as $key => $value) {
      if (gettype($value) == 'string')
        $args[$key] = '\''.$this->lnk->real_escape_string($args[$key]).'\'';
      else
        $args[$key] = $this->lnk->real_escape_string($args[$key]);
    }
    return vsprintf($format, $args);
  }
  //----------------------------------------------------- 
  
  //-------- Query --------------------------------------
  function query($qry) {
    return $this->lnk->query($qry);
  }
  //-----------------------------------------------------
  
  //-------- Number of rows -----------------------------
  function numRows($result) {
    return $result->num_rows;
  }
  //-----------------------------------------------------

  //-------- Result -------------------------------------
  /*function result($result, $index, $field) {
    return mysql_result($result, $index, $field);
  }*/
  //-----------------------------------------------------

  //-------- Fetch row ----------------------------------
	/*function fetchRow($result) {
		return mysql_fetch_row($result);
	}*/
	//-----------------------------------------------------

	//-------- Fetch object -------------------------------
  /*function fetchObject($result) {
    return mysql_fetch_object($result);
  }*/
  //-----------------------------------------------------

  //-------- Fetch array --------------------------------
  /*function fetchArray($result) {
    return mysql_fetch_array($result);
  }*/
  //-----------------------------------------------------

	//-------- Fetch assoc --------------------------------
  /*function fetchAssoc($result) {
    return mysql_fetch_assoc($result);
  }*/
  //-----------------------------------------------------

  //-------- Get the last inserted id -------------------
  function getInsertId() {
    return $this->lnk->insert_id;
  }
  //-----------------------------------------------------

  //-------- Get MySQL info -----------------------------
	/*function info() {
    return mysql_info($this->lnk);
  }*/
	//-----------------------------------------------------

	//-------- Get the number of affected rows ------------
	function affectedRows() {
		return $this->lnk->affected_rows;
	}
	//-----------------------------------------------------
}
?>
