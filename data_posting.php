<?php

require_once("common.class.php");
require_once("database.class.php");

class CreateRadioAppPost {

    // Main method to redeem a code
    function makePosting($radioApp) {
    	
    	if(is_array($radioApp)){
			
			$vars = array();
			
			foreach($radioApp as $k=>$v){
				$xssSafe = Common::cleanVar($v);
				$vars[$k] = Common::cleanSqlVar($xssSafe);									
			}
										
			$table = "mtvdedicateapp_posts";
			
			$query = Database::query('INSERT',$table,$vars);
			Database::runQuery($query);
			
			Database::sendResponse(200, json_encode('Entry Created'));
			
			return TRUE;
			
		} else {

			Database::sendResponse(400, json_encode('Invalid request'));
			
			return FALSE;
				
		}
    }
} 


// This is the first thing that gets called when this page is loaded
// Creates a new instance of the CreateRadioAppPost class and calls the makePosting method
$common = new Common;
$db = new Database;
$radio = new CreateRadioAppPost;

$radio->makePosting($_POST["Dedicated"]);

?>

<a href='test_bed.php'>Back to Test Bed</a>	

