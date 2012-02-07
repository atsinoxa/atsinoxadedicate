<?php
require_once("dedicated.dat");
require_once("common.class.php");
require_once("database.class.php");
error_reporting(0);

/*
This is a list of the people who have sent the most dedications via the app on either facebook or twitter that haven't marked their dedication private.

Do email dedications get included in the most sent list?
WSHandsetType : must be either iPhone or Android
WSHandsetModel : e.g. iPhone 4, Samsung Galaxy S, Samsung Galaxy Tab, etc.
WSHandsetOSVersion : e.g. iOS 4.3.2
WSChannel : must be either Twitter, Facebook
WSDedicationPrivate : flag indicating whether the user has marked the dedication as private - must be 0
WSDedicationSuccess : flag indicating whether the connection with Twitter, Facebook or email was successful - must be 1
WSSenderName : the name of the Sender
WSSenderTwitterID : the Sender's Twitter ID (if Twitter is used)
WSSenderFacebookID : the Sender's Facebook ID (if Facebook is used)
WSSenderQuantity : the number of dedications sent by the Sender
WSSenderTwitterThumbnail:
WSSenderFacebookThumbnail:
*/

class GetMostRecentDetails {

    // Main method to redeem a code
    function most_sent($results, $page) {
    		
			$vars = array(
				"WSHandsetType",
				"WSHandsetModel",
				"WSHandsetOSVersion",
				"WSChannel",
				"WSDedicationPrivate",
				"WSDedicationSuccess",
				"WSDedicationDateTime",
				"WSSenderName",
				"WSSenderTwitterID",
				"WSSenderFacebookID",
				"WSSenderEmail",
				"WSSenderQuantity",																		
			);
										
			$table = "mtvdedicateapp_posts";
			
			$resultDetails = array();
			
			if ($page > 0) {
				// the offset of the list, based on current page 
				$offset = ($page - 1) * $results;
			
				$query = "SELECT *, COUNT(*) AS WSSenderQuantity FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 AND WSDedicationPrivate = 0 ORDER BY WSSenderQuantity LIMIT ".$offset.", ".$results;
			} else {
				$query = "SELECT *, COUNT(*) AS WSSenderQuantity FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 AND WSDedicationPrivate = 0 ORDER BY WSSenderQuantity DESC LIMIT ".$results;
			}
				
			$resultDetails = Database::getAll($query,$vars, "WSSenderName");
									
			return $resultDetails;
    }
} 




// This is the first thing that gets called when this page is loaded
// Creates a new instance of the CreateRadioAppPost class and calls the makePosting method
$common = new Common;
$db = new Database;
$radio = new GetMostRecentDetails;

//get the current page - otherwise set it
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
   $page = (int) $_GET['page'];
} else {
      $page = "";
}

if ($_GET["format"]) {
	$format = $_GET["format"];
} 

if ($_GET["results"]) {
	$results_per_page = $_GET["results"];
}

$result = $radio->most_sent($results_per_page, $page);

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




