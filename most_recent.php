<?php
require_once("dedicated.dat");
require_once("common.class.php");
require_once("database.class.php");

error_reporting(0);

/*
This is a list of the most recent tracks that have been dedicated by all users of the app.

WSHandsetType : iPhone, Android
WSHandsetModel : e.g. iPhone 4, Samsung Galaxy S, Samsung Galaxy Tab, etc.
WSHandsetOSVersion : e.g. iOS 4.3.2
WSChannel : must be either Twitter, Facebook or Email
WSDedicationMessage : the dedication message sent by the user
WSDedicationVideoID; WSDedicationVideoTitle; WSDedicationVideoThumbnailID; WSDedicationVideoThumbnailHeight; WSDedicationVideoThumbnailWidth; WSDedicationVideoThumbnailType; WSDedicationVideoThumbnailURL; WSDedicationVideoURL; WSDedicationVideoArtistID; WSDedicationVideoArtistName : details of the video dedicated (from the original XML feed)
WSDedicationPrivate : flag indicating whether the user has marked the dedication as private - must be 0
WSDedicationSuccess : flag indicating whether the connection with Twitter, Facebook or email was successful - must be  1
WSDedicationDateTime : the time and date at which the dedication was made
WSSenderName : the name of the user sending the dedication
WSRecipientName : the name of the recipient
WSSenderTwitterID : the sender's Twitter ID (if available)
WSRecipientTwitterID : the recipient's Twitter ID (if Twitter is used)
WSSenderFacebookID : the sender's Facebook ID (if available)
WSRecipientFacebookID : the recipient's Facebook ID (if Facebook is used)
WSSenderEmailID : the sender's email address (if available)
WSRecipientEmailID : the recipient's email address (if available)
*/

class GetMostRecentDetails {

    // Main method to redeem a code
    function most_recent($results, $page) {
    		
			$vars = array(
				"WSPostID",
				"WSHandsetType",
				"WSHandsetModel",
				"WSHandsetOSVersion",
				"WSChannel",
				"WSDedicationMessage",
				"WSDedicationVideoID",
				"WSDedicationVideoTitle",
				"WSDedicationVideoThumbnailID",
				"WSDedicationVideoThumbnailH",
				"WSDedicationVideoThumbnailW",
				"WSDedicationVideoThumbnailType",
				"WSDedicationVideoThumbnailURL",
				"WSDedicationVideoURL",
				"WSDedicationVideoArtistID",
				"WSDedicationVideoArtistName",
				"WSDedicationPrivate",
				"WSDedicationSuccess",
				"WSDedicationDateTime",
				"WSSenderName",
				"WSRecipientName",
				"WSSenderTwitterID",
				"WSRecipientTwitterID",
				"WSSenderFacebookID",
				"WSRecipientFacebookID",
				"WSSenderEmail",
				"WSRecipientEmail"															
			);
										
			$table = "mtvdedicateapp_posts";
			
			$resultDetails = array();
			
			if ($page > 0) {
				// the offset of the list, based on current page 
				$offset = ($page - 1) * $results;
				$query = "SELECT * FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 AND WSDedicationPrivate = 0 ORDER BY WSDedicationDateTime DESC LIMIT ".$offset.", ".$results;
			} else {
				$query = "SELECT * FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 AND WSDedicationPrivate = 0 ORDER BY WSDedicationDateTime DESC LIMIT ".$results;
			}
			$resultDetails = Database::getAll($query,$vars, "WSPostID");
			
    		if (count($resultDetails) == 0) {
				$resultDetails["0"]["Error"] = "No results found";
			}
						
			return $resultDetails;
    }
} 




// This is the first thing that gets called when this page is loaded
// Creates a new instance of the CreateRadioAppPost class and calls the makePosting method
$common = new Common;
$db = new Database;
$radio = new GetMostRecentDetails;

//the default amount of resutls to display - taken from dedicated.dat
$results = $results_per_page;

//the default format is JSON
$format = "json";

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

$result = $radio->most_recent($results_per_page, $page);

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




