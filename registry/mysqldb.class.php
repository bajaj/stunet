<?php 
/* Database access class
@author Priyank Jain
@version 1.0
 * +__construct(Registry reference):void -> creates object of type mysqldb
 * +newConnection(hostname, username, password, database name): count of the latest connection in the array -> Creates a new connection
 * +closeConnection():void -> closes the active connection
 * +setActiveConnection(id of new connection in connections array):void -> sets activeConnection to value of id
 * +executeQuery(Query string):void -> executes query and stores records in $last variable
 * +cacheQuery(Query string): count($this->queryCache)-1 -> executes query and records it in queryCache array and returns the key of the query in the queryCache array!
 * +numRowsFromCache(cache id):number of rows in the results in the queryCache[cacheId] -> get the number of rows in the results of a particular queryCache query
 * +resultsFromCache(cache id): actual rows from queryCache($cacheId)->get the actual results of a particular query from the queryCache 
 * +cacheData($data):count($this->dataCache)-1 -> store data in data cache and get key of the stored data in dataCache array
 * +dataFromCache(cache id):dataCache($cacheId) -> get a particular data from the data cache!
 * +getRows():results stored in last variable -> get the records stored in last variable
 * +deleteRecords(table name, condition, limit) -> delete records and store results in $last variable
 * +insertRecords(table name, data to be stored of the form fields => values): true -> insert records and storer results in $last variable
 * +updateRecords(table name, changes of the form fields => values, condition): true -> update records and store results in $ last variable
 * +lastInsertId():last insert id -> get the id of the last inserted value!
 * +sanitizeData(data to be sanitized): sanitized data -> sanitizes data, makes it of the form to be inserted into the database. Stirp slashes and escape characters!
 * +numRows(): num of rows on $last record -> get the number of rows in the results of the last executed query!
 * +affectedRows():num of affected rows of $last record -> get the number of affected rows of the last executed query!
 * +__deconstruct():void -> close All Connections!
 */
class Mysqldb
{
/* Allows multiple database connections
each connection is stored as an element in an array and
the active connection is maintained in a variable
*/
private $connections=array();

/*Tells the DB object which connection to use
setActiveConnection($id) can be used to change this
*/
private $activeConnection= 0;

/*Queries which have been executed are cached for later use, primarily 
for use within the template engine
*/
private $queryCache=array();

/*Data which has been prepared is cached for later use, primarily
for use within the template engine
*/
private $dataCache=array();

/*Number of queries made during execution process*/
private $queryCounter=0;

/*Record of last query */
private $last;

/*Reference to the registry object*/
private $registry;

/*Construct our database object */
public function __construct(Registry $registry)
{
	$this->registry=$registry;
}

/*Create a new database connection
@param String $host database hostname
@param String $user database username
@param String $password database password
@param String $database database name
@return the id of the new connection
*/
public function newConnection($host,$user,$password,$database)
{
	$this->connections[]=new mysqli($host,$user,$password,$database);
	$connection_id=count($this->connections)-1;
	if(mysqli_connect_errno())//this function returns zero if no error occurs, else returns an error code of type int!
	{
		trigger_error('Error connecting to host '.$this->connections[$connection_id]->error,E_USER_ERROR);//This function creates a user defined error message, syntax: trigger_error(error_message,error_type);error_type can be E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE.
	}
        $this->activeConnection=key($this->connections);
	return $connection_id;
}

/*Close a connection
@return void*/
public function closeConnection()
{
	$this->connections[$this->activeConnection]->close();
}

/*Change which database connection is actively used for the next operation */
public function setActiveConnection(int $new)
{
	$this->activeConnection=$new;
}

/*Execute a query 
@param String $queryStr the query string
@return void
*/
public function executeQuery($queryStr)
{
    	if(!$result=$this->connections[$this->activeConnection]->query($queryStr))
	{
		trigger_error('Error executing query: '.$queryStr.' - '.$this->connections[$this->activeConnection]->error,E_USER_ERROR);
	}
	else
	{
		$this->last=$result;
	}
}

/*Store a query in the query cache for processing later
@param String $queryStr the query string
@return the pointer to the query in the cache
*/
public function cacheQuery($queryStr)
{
	if(!$result=$this->connections[$this->activeConnection]->query($queryStr))
	{
		trigger_error('Error executing and caching query: '.$this->connections[$this->activeConnection]->error,E_USER_ERROR);
	}
	else
	{
		$this->queryCache[]=$result;
		return count($this->queryCache)-1;
	}
}

/*Get the number of rows from the cache
@param int $cacheId the query cache pointer
@return the number of rows of the query
*/
public function numRowsFromCache($cacheId)
{
	return $this->queryCache[$cacheId]->num_rows;
}

/*Get the results of a cached query
@param int the query cache pointer
@return array of rows
*/
public function resultsFromCache($cacheId)
{
	return $this->queryCache[$cacheId]->fetch_array(MYSQLI_ASSOC);
}

/*Store data in the data cache
@param mixed the data
@return the pointer to the data in the cache
*/
public function cacheData($data)
{
	$this->dataCache[]=$data;
	return count($this->dataCache)-1;
}

/*
Get data from the cache
@param int the pointer to the data in the cache
@return the required data from the cache
*/
public function dataFromCache($cacheId)
{
	return $this->dataCache[$cacheId];
}

/*Get the rows of the most recently execute query
@return array of rows
*/
public function getRows()
{
	return $this->last->fetch_array(MYSQLI_ASSOC);
}

/*Delete records from the database
@param String $table the table from which rows are to be deleted
@param String $condition the condition on which rows are to be deleted
@param int $limit the number of rows to be removed
@return void
*/
public function deleteRecords($table,$condition,$limit)
{
	$limit=($limit=='')?'':'limit '.$limit;
	$delete="delete from {$table} where {$condition} {$limit}";
	$this->executeQuery($delete);
}

/*Update table in the database
@param String $table the table to be updated
@param String $changes array of changes as field=>value
@param String $condtion the condition on which the rows are to be updated
@return boolean
*/
public function updateRecords($table,$changes,$condition)
{
	$update='update '.$table.' set ';
	foreach($changes as $field=>$value)
	{
		      $update .= "`" . $field . "`='{$value}',";
	}
	
	//remove the trailing , (comma)
	$update = substr($update, 0, -1);
	if($condition!='')
	{
		$update.="where ".$condition;
	}
	$this->executeQuery($update); 
	return true;
}


/*Insert records into a database table
@param String $table the database table
@param String $data array of data to be inserted of the form field=>value
@return boolean
*/
public function insertRecords($table,$data)
{
	$fields="";
	$values="";
	foreach($data as $f=>$v)
	{
		$fields.="`".$f."`,";
		$values.=(is_numeric($v)&&(intval($v)==$v))?$v.',':"'{$v}',";
	}
	
	$fields=substr($fields,0,-1);
	$values=substr($values,0,-1);
	
	$insert="insert into {$table} ({$fields}) values ({$values})";
	$this->executeQuery($insert);
	return true;
}

/*Get the last insert id
@return the last insert id*/
public function lastInsertID()
{
	return $this->connections[$this->activeConnection]->insert_id;
}

/*Sanitize data
@param String $value the data to be sanitized
@return String $value the sanitized data
*/
public function sanitizeData($value)
{
	//Stripslashes
	if(get_magic_quotes_gpc())
	{
		$value=stripslashes($value);	
	}
	//If the php version is less than 4.3.0 use escape_string, else use real_escape_string for higher versions!
	if(version_compare(phpversion(),"4.3.0")=="-1")
	{
		$value=$this->connections[$this->activeConnection]->escape_string($value);
	}
	else
	{
		$value=$this->connections[$this->activeConnection]->real_escape_string($value);
	}
	return $value;
}

/*Get the number of rows of the last executed query, excluding cached queries
@return number of rows in array*/
public function numRows()
{
	return $this->last->num_rows;
}

/*Get the number of affected rows of the last executed query
@return number of affected rows
*/
public function affectedRows()
{
	return $this->connections[$this->activeConnection]->affected_rows;
}

/*Deconstruct the object
Close all of the database connections
*/
public function __deconstruct()
{
	foreach($this->connections as $connection )
	{
		$connection->close();
	}
}
}

?>