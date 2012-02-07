<?php
require_once("dedicated.dat");
require_once("common.class.php");
require_once("database.class.php");
error_reporting(0);


/* This is a list of the dedications that the user has personally sent via twitter, Facebook and Email.
   Assumption that private dedications are also listed here.

	WSHandsetType : must be either iPhone or Android
	WSHandsetModel : e.g. iPhone 4, Samsung Galaxy S, Samsung Galaxy Tab, etc.
	WSHandsetOSVersion : e.g. iOS 4.3.2
	WSChannel : must be either Twitter or Facebook
	WSDedicationMessage : the dedication message sent by the user
	WSDedicationVideoID; WSDedicationVideoTitle; WSDedicationVideoThumbnailID; WSDedicationVideoThumbnailHeight; WSDedicationVideoThumbnailWidth; WSDedicationVideoThumbnailType; WSDedicationVideoThumbnailURL; WSDedicationVideoURL; WSDedicationVideoArtistID; WSDedicationVideoArtistName : details of the video dedicated (from the original XML feed)
	WSDedicationSuccess : flag indicating whether the connection with Twitter, Facebook or email was successful - must be  1
	WSDedicationDateTime : the time and date at which the dedication was made
	WSSenderName : the name of the user sending the dedication
	WSRecipientName : the name of the recipient
	WSSenderTwitterID : the sender's Twitter ID (if available)
	WSRecipientTwitterID : the recipient's Twitter ID (if Twitter is used)
	WSSenderFacebookID : the sender's Facebook ID (if available)
	WSRecipientFacebookID : the recipient's Facebook ID (if Facebook is used) 
*/

class GetReceivedDedications {

    // Main method to redeem a code
    function received_dedications($receiverTwitter, $receiverFacebook, $receiverEmail, $results, $page) {
    		
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
				"WSDedicationVideoThumbnailHeight",
				"WSDedicationVideoThumbnailWidth",
				"WSDedicationVideoThumbnailType",
				"WSDedicationVideoThumbnailURL",
				"WSDedicationVideoURL",
				"WSDedicationVideoArtistID",
				"WSDedicationVideoArtistName",		
				"WSDedicationSuccess",
				"WSDedicationDateTime",
				"WSSenderName",
				"WSSenderTwitterID",
				"WSRecipientTwitterID",
				"WSSenderFacebookID",
				"WSRecipientFacebookID",
				"WSSenderEmail",	
				"WSRecipientEmail",
				"WSRecipientName"
			);
										
			$table = "mtvdedicateapp_posts";
			
    		//couple of permutations here
			//1 - Both Facebook & TwitterID & Email are present
			//2 - Facebook ID & Email are present but not TwitterID
			//3 - TwitterID & Email are present but not Facebook
			//4 - FacebookID & TwitterID are present but not Email
			//5 - Email is present but not FacebookID or TwitterID
			//6 - FacebookID is present but not Email or TwitterID
			//7 - TwitterID is present but not FacebookID or Email
					
			$EXTRAS = "";
			if ($receiverTwitter != "" && $receiverFacebook != "" && $receiverEmail != "") {
				$EXTRAS = "AND (WSRecipientTwitterID = '".$receiverTwitter."' OR WSRecipientFacebookID = '".$receiverFacebook."' OR WSRecipientEmail = '".$receiverEmail."')";
			} else if ($receiverTwitter == "" && $receiverFacebook != "" && $receiverEmail != "") {
				$EXTRAS = "AND (WSRecipientEmail = '".$receiverEmail."' OR WSRecipientFacebookID = '".$receiverFacebook."')";
			} else if ($receiverTwitter != "" && $receiverFacebook == "" && $receiverEmail != "") {
				$EXTRAS = "AND (WSRecipientEmail = '".$senderEmail."' OR WSRecipientTwitterID = '".$senderTwitter."')";
			} else if ($receiverTwitter != "" && $receiverFacebook != "" && $receiverEmail == "") {
				$EXTRAS = "AND (WSRecipientFacebookID = '".$receiverFacebook."' OR WSRecipientTwitterID = '".$senderTwitter."')";
			} else if ($receiverTwitter == "" && $receiverFacebook == "" && $receiverEmail != "") {
				$EXTRAS = "AND WSRecipientEmail = '".$receiverEmail."'";
			} else if ($receiverTwitter == "" && $receiverEmail == "" && $receiverFacebook != "") {
				$EXTRAS = "AND WSRecipientFacebookID = '".$receiverFacebook."'";
			} else if ($receiverTwitter != "" && $receiverFacebook == "" && $receiverEmail == "") { 
				$EXTRAS = "AND WSRecipientTwitterID = '".$receiverTwitter."'";
			} else {
				$EXTRAS = "";
			}
			

			$resultDetails = array();
			
			if ($page > 0) {
				// the offset of the list, based on current page 
				$offset = ($page - 1) * $results;
				$query = "SELECT * FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 ".$EXTRAS." ORDER BY WSDedicationDateTime DESC LIMIT ".$offset.", ".$results;
			} else {
				$query = "SELECT * FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 ".$EXTRAS." ORDER BY WSDedicationDateTime DESC LIMIT ".$results;
			}
			
			
			$resultDetails = Database::getAll($query,$vars, "WSPostID");
			
			if (count($resultDetails) == 0) {
				$resultDetails["0"]["Error"] = "No results for this Receipient";
			}
									
			return $resultDetails;
    }
} 

// This is the first thing that gets called when this page is loaded
// Creates a new instance of the GetReceivedDedications class and calls the makePosting method
$common = new Common;
$db = new Database;
$radio = new GetReceivedDedications;

if ($_GET["format"]) {
	$format = $_GET["format"];
} else {
	$format = 'json';
}

if ($_GET["results"]) {
	$results = $_GET["results"];
} else {
	$results = $results_per_page;
}

if ($_GET["receiverTwitter"]) {
	$receiverTwitter = htmlspecialchars($_GET["receiverTwitter"]);
}

if ($_GET["receiverFacebook"]) {
	$receiverFacebook = htmlspecialchars($_GET["receiverFacebook"]);
}

if ($_GET["receiverEmail"]) {
	$receiverEmail = htmlspecialchars($_GET["receiverEmail"]);
}

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
   $page = (int) $_GET['page'];
} else {
      $page = "";
}

$result = $radio->received_dedications($receiverTwitter, $receiverFacebook, $receiverEmail, $results, $page);

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