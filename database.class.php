<?php

	/**
	 * Database.
	 * 
	 * <p>Stores all of the database related functions.</p>
	 *
	 * @subpackage Classes
	 * @version 1.0
	 * @since 1.0
	 */
	 
	 /**
	 * Opens a persistent connection to the database
	 * @return null
	 */
	if($_SERVER['HTTP_HOST'] == 'localhost:8888'){
       mysql_pconnect('localhost','root','root') or die ('Can\'t connect to database servers');
       mysql_select_db('mtvdedicateapp') or die ('Can\'t select database');
	} else {
       mysql_pconnect('a.db.shared.orchestra.io','user_02c02b04','(QEDy3qmPyAho-') or die ('Can\'t connect to database server');
       mysql_select_db('db_02c02b04') or die ('Can\'t select database');
	}
	 
    /**
	 * The parent class for all database functions on the site
	 *
	 * @subpackage Classes
	 */
	class Database {
		
		/**
		 * Performs a query based on the supplied arrays. All values in each array are sanitised using @see Database::CleanVar()
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param array $operation SELECT|INSERT INTO|UPDATE|DELETE only
		 * @param array $vars the fields that are to be retreived
		 * @param array $table the tables the table(s) to retrieve the fields from
		 * @param array $where to add a where clause to the query
		 * @return mixed sanitized mysql query string
		 */
		function query($operation,$table,$vars,$where=NULL,$order=NULL,$limit=NULL)
		{
			
			switch($operation){
				
				case 'SELECT':	
					$query = "SELECT ";
					// Fields
					if(is_array($vars)){
				
						foreach($vars as $k=>$v){
						
							$columns[] = Common::cleanSqlVar($v);
						
						}
						
						$fields = implode(',',$columns);
						
						$query .= $fields . " FROM ";
					
					} else {
						
						$_SESSION['error'] = 'Variables must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
						header('location:' . ROOT . 'error');
						exit();
					
					}
					// Tables
					if(is_array($table)){
				
						foreach($table as $k=>$v){
						
							$tables[] = Common::cleanSqlVar($v);
						
						}
						
						$tables = implode(',',$tables);
						
						$query .= $tables;
					
					} else {
						
						$_SESSION['error'] = 'Tables must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
						header('location:' . ROOT . 'error');
						exit();
					
					}
					
					if(isset($where)){
						
						$query .= " WHERE ";
						
						// Where
						if(is_array($where)){
					
							foreach($where as $k=>$v){
							
								$wheres[] = $k . "'" . Common::cleanSqlVar($v) . "'";
							
							}
							
							$wheres = implode(' AND ',$wheres);
							
							$query .= $wheres;
						
						} else {
							
							$_SESSION['error'] = 'WHERE clause must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
							header('location:' . ROOT . 'error');
							exit();
						
						}
						
					}
					if(isset($order)){
						
						$query .= " ORDER BY ";
						
						// Where
						if(is_array($order)){
					
							foreach($order as $k=>$v){
							
								$orders[] = $k . ' ' . Common::cleanSqlVar($v);
							
							}
							
							$orders = implode(', ',$orders);
							
							$query .= $orders;
						
						} else {
							
							$_SESSION['error'] = 'ORDER BY clause must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
							header('location:' . ROOT . 'error');
							exit();
						
						}
						
					}
					if(isset($limit)){
						
						$query .= " LIMIT " . $limit;
						
					}
				break;
				case 'INSERT':
					$query = "INSERT INTO ";
					
					// Tables
					$query .= $table;
					
					// Fields
					if(is_array($vars)){
				
						foreach($vars as $k=>$v){
						
							$keys[] = Common::cleanSqlVar($k);
							$values[] = Common::cleanSqlVar($v,TRUE);
						
						}
						
						$keys = implode(',',$keys);
						$values = implode(',',$values);
						
						$query .= ' (' . $keys . ') VALUES (' . $values . ')';
					
					} else {
						
						$_SESSION['error'] = 'Variables must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
						header('location:' . ROOT . 'error');
						exit();
					
					}
				break;
				#########################
				#########################
				case 'DELETE':	
					$query = "DELETE FROM ";
					// Tables
					if(is_array($table)){
				
						foreach($table as $k=>$v){
						
							$tables[] = Common::cleanSqlVar($v);
						
						}
						
						$tables = implode(',',$tables);
						
						$query .= $tables;
					
					} else {
						
						$_SESSION['error'] = 'Tables must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
						header('location:' . ROOT . 'error');
						exit();
					
					}
					
					if(isset($where)){
						
						$query .= " WHERE ";
						
						// Where
						if(is_array($where)){
					
							foreach($where as $k=>$v){
							
								$wheres[] = $k . "'" . Security::cleanSqlVar($v) . "'";
							
							}
							
							$wheres = implode(' AND ',$wheres);
							
							$query .= $wheres;
						
						} else {
							
							$_SESSION['error'] = 'WHERE clause must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
							header('location:' . ROOT . 'error');
							exit();
						
						}
						
					}
				break;
				#########################
				#########################
				case 'UPDATE':
					$query = "UPDATE ";
					
					// Tables
					$query .= $table[0] . " SET ";
					
					// Fields
					if(is_array($vars)){
				
						$keyCount = 0;
						
						foreach($vars as $k=>$v){
						
							$keys[] = Common::cleanSqlVar($k);
							$values[] = Common::cleanSqlVar($v,TRUE);
							$keyCount++;
						
						}
						
						for($i = 0; $i < $keyCount; $i++){
							
							$query .= $keys[$i] . "=" . $values[$i];
							
							if($i != ($keyCount-1)){
							
								$query .= ", ";
							
							} else {
							
								$query .= " ";
							
							}
							
						}
					
					} else {
						
						$_SESSION['error'] = 'Variables must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
						header('location:' . ROOT . 'error');
						exit();
					
					}
					
					// Where
					if(is_array($where)){
						
						$query .= "WHERE ";
				
						foreach($where as $k=>$v){
						
							$wheres[] = $k . "'" . Common::cleanSqlVar($v) . "'";
						
						}
						
						$wheres = implode(' AND ',$wheres);
						
						$query .= $wheres;
					
					} else {
						
						$_SESSION['error'] = 'WHERE clause must be passed as array to Database::query(); on line ' . __LINE__ . ' of ' . __FILE__;
						header('location:' . ROOT . 'error');
						exit();
					
					}
				break;
				default:
					$_SESSION['error'] = 'Undefined SQL operation provided.';
					header('location:' . ROOT . 'error');
					exit();
				break;
			 
			}
			
			return $query;
			
		}
		
		/**
		 * Gets a single field from a row in the database
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string $query already sanitized and ready for use
		 * @return mixed value of the field
		 */
		 function getOne($query,$var)
		 {
			 
			 $result = mysql_query($query);
			 return @mysql_result($result,0,$var);
			 
			 
		 }
		 
		 /**
		 * Gets a set of fields from the matching rows
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string $query already sanitized and ready for use
		 * @param array the field names to retreive
		 * @return array of the fields from the desired rows
		 */
		 function getRows($query,$vars)
		 {
			 
			 $result = mysql_query($query);
			 
			 $rowResult = array();
			 
			 while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			 
			 	foreach($vars as $v){
				
					$rowResult[$v] = stripslashes($row[$v]);
					
				}
			 
			 }
			 
			 return $rowResult;
			 
			 
		 }
		 
		 /**
		 * Gets the fields from all rows dictated by the query
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string $query already sanitized and ready for use
		 * @param array $vars the field names to retreive
		 * @param string $idField the id field
		 * @return array of the fields from the desired rows
		 */
		 function getAll($query,$vars,$idField)
		 {
		 	
			 $result = mysql_query($query);
			 
			 $rowResult = array();
			 
			 while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			 	
			 	$id = $row[$idField];
				
				foreach($vars as $v){
				
					$rowResult[$id][$v] = stripslashes($row[$v]);
					
				}
			 
			 }
			 
			 return $rowResult;
			 
		 }
		 
		 /**
		 * Gets a configuration value from the database
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string setting the key from the config table
		 * @return mixed value the value of the setting
		 */
		 function getConfig($setting)
		 {
			 
			$table = array("sys_config");
			$vars = array("value");
			$where = array("setting=" => $setting);
			
			$query = Database::query('SELECT',$table,$vars,$where);
			
			return Database::getOne($query,$vars[0]);
			 
			 
		 }
		 
		 /**
		 * Performs a simple query on the database
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string $query already sanitized and ready for use
		 * @param string $return whether to return the query as a mysql_result (optional)
		 * @return null
		 */
		 function runQuery($query,$return=FALSE)
		 {
			 
			 $result = mysql_query($query);
			 
			 if($return){
				 
				 return $result;
				 
			 }
			 
		 }

		 		 /**
		 * Performs a simple query on the database - return last insertid
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string $query already sanitized and ready for use
		 * @param string $return whether to return the query as a mysql_result (optional)
		 * @return null
		 */
		 function runReturnQuery($query,$return=FALSE)
		 {
			 
			 $result = mysql_query($query);
			 
			 if($result){
				 
				 return mysql_insert_id();
				 
			 }
			 
		 }
		 
		 /**
		 * Check if a record already exists in a field in a database
		 *
		 * @package KinsaleOutDoors
		 * @subpackage Classes
		 *
		 * @param string $table which table we are querying
		 * @param string $field which field we are checking
		 * @param string $value the value we are looking for
		 * @return BOOL TRUE if the record already exists
		 */
		 function recordExists($table,$field,$value)
		 {
			 
			$table = array("$table");
			$vars = array("*");
			$where = array("$field=" => $value);
			
			$query = Database::query('SELECT',$table,$vars,$where);
			$result = Database::runQuery($query,TRUE);
			
			if(mysql_num_rows($result) == 1){
				
				return TRUE;
				
			} else {
			
				return FALSE;
			
			}
			 
			 
		 }
		 
		 
			//handy list of codes to return to the App just in case there is a problem!
		function getStatusCodeMessage($status)
		{
		    $codes = Array(
		        100 => 'Continue',
		        101 => 'Switching Protocols',
		        200 => 'OK',
		        201 => 'Created',
		        202 => 'Accepted',
		        203 => 'Non-Authoritative Information',
		        204 => 'No Content',
		        205 => 'Reset Content',
		        206 => 'Partial Content',
		        300 => 'Multiple Choices',
		        301 => 'Moved Permanently',
		        302 => 'Found',
		        303 => 'See Other',
		        304 => 'Not Modified',
		        305 => 'Use Proxy',
		        306 => '(Unused)',
		        307 => 'Temporary Redirect',
		        400 => 'Bad Request',
		        401 => 'Unauthorized',
		        402 => 'Payment Required',
		        403 => 'Forbidden',
		        404 => 'Not Found',
		        405 => 'Method Not Allowed',
		        406 => 'Not Acceptable',
		        407 => 'Proxy Authentication Required',
		        408 => 'Request Timeout',
		        409 => 'Conflict',
		        410 => 'Gone',
		        411 => 'Length Required',
		        412 => 'Precondition Failed',
		        413 => 'Request Entity Too Large',
		        414 => 'Request-URI Too Long',
		        415 => 'Unsupported Media Type',
		        416 => 'Requested Range Not Satisfiable',
		        417 => 'Expectation Failed',
		        500 => 'Internal Server Error',
		        501 => 'Not Implemented',
		        502 => 'Bad Gateway',
		        503 => 'Service Unavailable',
		        504 => 'Gateway Timeout',
		        505 => 'HTTP Version Not Supported'
		    );
		 
		    return (isset($codes[$status])) ? $codes[$status] : '';
		}

		// Helper method to send a HTTP response code/message
		function sendResponse($status = 200, $body = '', $content_type = 'text/html')
		{
		    $status_header = 'HTTP/1.1 ' . $status . ' ' . Database::getStatusCodeMessage($status);
		    header($status_header);
		    header('Content-type: ' . $content_type);
		    echo $body;
		}
	
	}
	 
?>