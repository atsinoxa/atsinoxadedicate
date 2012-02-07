<?php
require_once("dedicated.dat");
require_once("common.class.php");
require_once("database.class.php");

error_reporting(0);

/*
This is a list of the most recent tracks that have been dedicated by all users of the app.
*/


class GetMostRecentPages {

    // Main method to redeem a code
    function most_recent($results) {
    												
			$table = "mtvdedicateapp_posts";
			
			$vars = array(
			  "num"	
			);
			
			$resultDetails = array();
			$query = "SELECT COUNT(*) AS num FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 AND WSDedicationPrivate = 0";
			$resultDetails = Database::getRows($query,$vars);
			
			// find out total pages
			$pages["0"]["pages"] = ceil($resultDetails["num"] / $results);
			
			return $pages;
    }
} 




// This is the first thing that gets called when this page is loaded
// Creates a new instance of the CreateRadioAppPost class and calls the makePosting method
$common = new Common;
$db = new Database;
$radio = new GetMostRecentPages;

//the default format is JSON
$format = "json";

if ($_GET["format"]) {
	$format = $_GET["format"];
} 

if ($_GET["results"]) {
	$results_per_page = $_GET["results"];
} 

$result = $radio->most_recent($results_per_page);

/* output in necessary format */
  if($format == 'json') {
    echo json_encode(array('results'=>$result));
  } else {
  	  	
  	header('Content-type: text/xml');
    echo '<results>';
    foreach($result as $key => $details) {
      echo '<result>';	
      if(is_array($details)) {
        foreach($details as $keys => $value) {
              echo '<',$keys,'>',htmlentities($value),'</',$keys,'>';
        }
        echo '</result>';
      }
    }
    echo '</results>';
  }




