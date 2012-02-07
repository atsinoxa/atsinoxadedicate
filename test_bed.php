<?php


$radioApp = array();

$radioApp["WSHandsetType"] = "iPhone"; 
$radioApp["WSHandsetModel"] = "3GS";
$radioApp["WSHandsetOSVersion"] = "iOS 4.3.2";
$radioApp["WSChannel"] = "Twitter";
$radioApp["WSDedicationMessage"] = "Hello World - Welcome to the App";
$radioApp["WSDedicationVideoID"] = "1234";
$radioApp["WSDedicationVideoTitle"] = "A Test Title Video";
$radioApp["WSDedicationVideoThumbnailID"] = "2345";
$radioApp["WSDedicationVideoThumbnailHeight"] = "90";
$radioApp["WSDedicationVideoThumbnailWidth"] = "120";
$radioApp["WSDedicationVideoThumbnailType"] = "GIF";
$radioApp["WSDedicationVideoThumbnailURL"] = "http://www.example.com/thumbnails/THUMB.gif";
$radioApp["WSDedicationVideoURL"] = "http://www.example.com/video.php";
$radioApp["WSDedicationVideoArtistName"] = "Snoop Dog";
$radioApp["WSDedicationPrivate"] = "1";
$radioApp["WSDedicationSuccess"] = "0";
$radioApp["WSDedicationFailMessage"] = "Error connecting...";
$radioApp["WSSenderName"] = "Jason Bloggs";
$radioApp["WSRecipientName"] = "Joe Bloggs";
$radioApp["WSSenderTwitterID"] = "jason_bloggs";
$radioApp["WSRecipientTwitterID"] = "joe_bloggs";
$radioApp["WSSenderFacebookID"] = "jason_facebook";
$radioApp["WSRecipientFacebookID"] = "joe_facebook";
$radioApp["WSSenderEmail"] = "jason@facebook.com";
$radioApp["WSRecipientEmail"] = "joe@facebook.com";

$date_time = date("Y-m-d h:m:s");

?>
<h1>APP Test Bed</h1>

<p>Insert a POST entry into Database Table:</p>
<form method='post' action='data_posting.php' method="post">
 <input type='submit' value='submit'>
 <input type='hidden' name='Dedicated[WSHandsetType]' value='iPhone'></input>
 <input type='hidden' name='Dedicated[WSHandsetModel]' value='3Gs'></input>
 <input type='hidden' name='Dedicated[WSHandsetOSVersion]' value='iOS 4.3.2'></input>
 <input type='hidden' name='Dedicated[WSChannel]' value='Twitter'></input> 
 <input type='hidden' name='Dedicated[WSDedicationMessage]' value='Hello World - Welcome to the App'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoID]' value='1237'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoTitle]' value='A Test Title Video'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoThumbnailID]' value='2345'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoThumbnailH]' value='90'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoThumbnailW]' value='120'></input> 
 <input type='hidden' name='radioApp[WSDedicationVideoThumbnailType]' value='GIF'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoThumbnailURL]' value='http://www.example.com/thumbnails/THUMB.gif'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoURL]' value='http://www.example.com/video.php'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoArtistID]' value='2'></input>
 <input type='hidden' name='Dedicated[WSDedicationVideoArtistName]' value='Snoop Dog'></input>
 <input type='hidden' name='Dedicated[WSDedicationPrivate]' value='0'></input>
 <input type='hidden' name='Dedicated[WSDedicationSuccess]' value='1'></input> 
 <input type='hidden' name='Dedicated[WSDedicationDateTime]' value='<?=$date_time; ?>'></input>  
 <input type='hidden' name='Dedicated[WSDedicationFailMessage]' value=''></input>
 <input type='hidden' name='Dedicated[WSSenderName]' value='Jason Bloggs'></input>
 <input type='hidden' name='Dedicated[WSRecipientName]' value='Joe Bloggs'></input>
 <input type='hidden' name='Dedicated[WSSenderTwitterID]' value='jason_bloggs'></input>
 <input type='hidden' name='Dedicated[WSRecipientTwitterID]' value='joe_bloggs'></input>
 <input type='hidden' name='Dedicated[WSSenderFacebookID]' value='jason_facebook'></input> 
 <input type='hidden' name='Dedicated[WSRecipientFacebookID]' value='joe_facebok'></input>
 <input type='hidden' name='Dedicated[WSSenderEmail]' value='jason@gmail.com'></input> 
 <input type='hidden' name='Dedicated[WSRecipientEmail]' value='joe@gmail.com'></input>        
</form>
<p>&nbsp;</p>

<p>Get Most Recent Results in JSON format: <a href='most_recent.php?format=json'>most_recent.php?format=json</a></p>

<p>Get Most Received Results in JSON format: <a href='most_received.php?format=json'>most_received.php?format=json</a></p>

<p>Get Most Dedicated Results in JSON format: <a href='most_dedicated.php?format=json'>most_dedicated.php?format=json</a></p>

<p>Get Most Sent Results in JSON format: <a href='most_sent.php?format=json'>most_sent.php?format=json</a></p>

<p>Get My Received Dedications By Sender in JSON format: <a href='received_dedications.php?format=json&receiverTwitter=joe_bloggs&receiverFacebook=joe_facebok&receiverEmail=joe@gmail.com'>received_dedications.php?format=json&receiverTwitter=joe_bloggs&receiverFacebook=joe_facebok&receiverEmail=joe@gmail.com</a></p>

<p>Get My Sent Dedications By Sender in JSON format: <a href='sent_dedications.php?format=json&senderTwitter=jason_bloggs&senderFacebook=jason_facebook&senderEmail=jason@gmail.com'>sent_dedications.php?format=json&senderTwitter=jason_bloggs&senderFacebook=jason_facebook&senderEmail=jason@gmail.com</a></p>

<p><b>Page Counts</b></p>
<p>Get Page Count for Most Recent Results in JSON format: <a href='most_recent_pages.php?format=json'>most_recent_pages.php?format=json</a></p>
<p>Get Page Count for Most Received Results in JSON format: <a href='most_received_pages.php?format=json'>most_received_pages.php?format=json</a></p>
<p>Get Page Count for Most Dedicated Results in JSON format: <a href='most_dedicated_pages.php?format=json'>most_dedicated_pages.php?format=json</a></p>
<p>Get Page Count for Most Sent Results in JSON format: <a href='most_sent_pages.php?format=json'>most_sent_pages.php?format=json</a></p>
<p>Get Page Count for My Received Dedications By Sender in JSON format: <a href='received_dedications_pages.php?format=json&receiverTwitter=joe_bloggs&receiverFacebook=joe_facebok&receiverEmail=joe@gmail.com'>received_dedications_pages.php?format=json</a></p>
<p>Get Page Count for My Sent Dedications By Sender in JSON format: <a href='sent_dedications_pages.php?format=json&senderTwitter=jason_bloggs&senderFacebook=jason_facebook&senderEmail=jason@gmail.com'>sent_dedications_pages.php?format=json</a></p>
<p>&nbsp;</p>

