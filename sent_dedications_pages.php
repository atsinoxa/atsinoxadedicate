<?php
require_once("dedicated.dat");
require_once("common.class.php");
require_once("database.class.php");
error_reporting(0);


/* This is a list of the dedications that the user has personally sent via twitter, Facebook and Email.
   Assumption that private dedications are also listed here.
*/

class GetSentDedications {

    // Main method to redeem a code
    function sent_dedications($senderTwitter, $senderFacebook, $senderEmail, $results, $page) {
    		
			$vars = array(
				"num"															
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
			if ($senderTwitter != "" && $senderFacebook != "" && $senderEmail != "") {
				$EXTRAS = "AND (WSSenderTwitterID = '".$senderTwitter."' OR WSSenderFacebookID = '".$senderFacebook."' OR WSSenderEmail = '".$senderEmail."')";
			} else if ($senderTwitter == "" && $senderFacebook != "" && $senderEmail != "") {
				$EXTRAS = "AND (WSSenderEmail = '".$senderEmail."' OR WSSenderFacebookID = '".$senderFacebook."' OR WSSenderEmail = '".$senderEmail."')";
			} else if ($senderTwitter != "" && $senderFacebook == "" && $senderEmail != "") {
				$EXTRAS = "AND (WSSenderEmail = '".$senderEmail."' OR WSSenderTwitterID = '".$senderTwitter."' OR WSSenderEmail = '".$senderEmail."')";
			} else if ($senderTwitter != "" && $senderFacebook != "" && $senderEmail == "") {
				$EXTRAS = "AND (WSSenderFacebookID = '".$senderFacebook."' OR WSSenderTwitterID = '".$senderTwitter."' OR WSSenderEmail = '".$senderEmail."')";
			} else if ($senderTwitter == "" && $senderFacebook == "" && $senderEmail != "") {
				$EXTRAS = "AND WSSenderEmail = '".$senderEmail."'";
			} else if ($senderTwitter == "" && $senderEmail == "" && $senderFacebook != "") {
				$EXTRAS = "AND WSSenderFacebookID = '".$senderFacebook."'";
			} else if ($senderTwitter != "" && $senderFacebook == "" && $senderEmail == "") { 
				$EXTRAS = "AND WSSenderTwitterID = '".$senderTwitter."'";
			} else {
				$EXTRAS = "";
			}
			
			$resultDetails = array();
			
			$query = "SELECT COUNT(*) AS num FROM mtvdedicateapp_posts WHERE WSDedicationSuccess = 1 ".$EXTRAS;

			$resultDetails = Database::getRows($query,$vars);
									
			// find out total pages
			$pages["0"]["pages"] = ceil($resultDetails["num"] / $results);
			
			return $pages;
    }
} 




// This is the first thing that gets called when this page is loaded
// Creates a new instance of the GetSentDedications class and calls the makePosting method
$common = new Common;
$db = new Database;
$radio = new GetSentDedications;

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

if ($_GET["senderTwitter"]) {
	$senderTwitter = htmlspecialchars($_GET["senderTwitter"]);
}

if ($_GET["senderFacebook"]) {
	$senderFacebook = htmlspecialchars($_GET["senderFacebook"]);
}

if ($_GET["senderEmail"]) {
	$senderEmail = htmlspecialchars($_GET["senderEmail"]);
}


$result = $radio->sent_dedications($senderTwitter, $senderFacebook, $senderEmail, $results);

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