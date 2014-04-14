<?

/*
This script, a cron job, pulls wedform information from a drupal database,
then sends an email with daily entry numbers and an email with a CSV of all entries.
*/

// Load email library 
require_once('PHPMailer-master/class.phpmailer.php');


//Main function for pulling data and sending emails.
function send_reports($property) {
	
	//Setup Property Variables.
	$from = 'from@address.com';
	$recipients =  array('email1@test.com', 'email2@test.com'); 
	
	switch ($property) {
    case "property1":
        $database = 'database1';
		$propname = 'Client 2';
		$nid = 1;
        break;
    case "property2":
        $database = 'database2';
		$propname = 'Client 3';
		$nid = 2;
        break;

	}
	
	// Connect to database
	$mysqli = new mysqli('url.com', 'username', 'password', $database);
	
	// Build Message Table
	$message = '<html><head><title>Email Report</title></head>';
	$message .= "<body bgcolor='#000088'><table width='100%' bgcolor='#000088'><tr><td align='center'><table bgcolor='#ffffff' style='border:solid; border-width:2px; border-color: gray;'><tr><td align='center'>";
	$message .="<table><tr><td align='right'>Promotion Name:</td><td>$propname Promotion</td></tr><tr><td align='right'>Start Date:</td><td>DATE</td></tr><tr><td align='right'>End Date:</td><td>DATE</td></tr></table>";
	$message .= "<table cellpadding='5' cellspacing='0' border='1'>";
	$message .= "<tr><td></td>";
	
	//Create row of dates
	date_default_timezone_set('America/New_York'); 
	for ($i=1; $i < 9; $i++) { 
		$message .= '<td>'. date("M d", mktime(0, 0, 0, date("m")  , date("d") - $i , date("Y"))). '</td>';
	}
	$message .= '<td><b>Total</b></td>';
	
	//Create row of daily entries
	$message .= '</tr><tr><td align="right" style="text-align: right;">Entries:</td>';
	$result = $mysqli->query('SELECT * FROM webform_submissions WHERE nid = '.$nid.' AND submitted BETWEEN UNIX_TIMESTAMP( DATE_SUB( NOW(), INTERVAL 1 DAY) ) AND UNIX_TIMESTAMP( NOW() );');
	$message .= '<td align="center" style="text-align: center;">' . $result->num_rows . '</td>';
	for ($i=2; $i < 9; $i++) { 
		$result = $mysqli->query( 'SELECT * FROM webform_submissions WHERE nid = '. $nid .' AND submitted BETWEEN UNIX_TIMESTAMP( DATE_SUB( NOW(), INTERVAL '. $i. ' DAY) ) AND UNIX_TIMESTAMP( DATE_SUB( NOW(), INTERVAL ' . ($i - 1) . ' DAY  ) );' );
		$message .= '<td align="center" style="text-align: center;">' . $result->num_rows .'</td>';
	}
	
	// Count total submissions.
	$result = $mysqli->query("SELECT * FROM webform_submissions where nid=".$nid);
	$message .= '<td align="center" style="text-align: center;"><b>' . $result->num_rows . '</b></td></tr>';
	
	$message .= '</table></td></tr></table></td></tr></table></body></html>';
	
	// Mail table.
	$email = new PHPMailer();
	$email->From      = $from;
	$email->FromName  = $from;
	$email->Subject   = $propname. ' Promotion Entries Report';
	$email->Body      = $message;
	foreach($recipients as $add ){
	   $email->AddCC($add);
	}
	$email->IsHTML(true);
	$email->Send();

	// Create CSV
	$result = $mysqli->query( "SELECT DISTINCT sid, GROUP_CONCAT(data ORDER BY cid SEPARATOR '--SEPPY--') AS data_list FROM webform_submitted_data WHERE nid = ". $nid ." GROUP BY sid;" );
	$filename= $property . 'email' . date('M-d') . '.csv';
	$filepath='/data/'. $filename;
	$fp = fopen($filename, 'w');
	//fputcsv($fp, $headers);
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
		fputcsv($fp, explode("--SEPPY--",$row[1]));
	}
	fclose($fp);
	
	//Email CSV
	$email = new PHPMailer();
	$email->From      = $from;
	$email->FromName  = $from;
	$email->Subject   = $propname. ' Submissions CSV';
	$email->Body      = 'CSV of registrant data is attached.';
	foreach($recipients as $add ){
	   $email->AddCC($add);
	}
	$email->AddAttachment( $filepath , $filename );
	return $email->Send();
}

echo send_reports('property1');
echo send_reports('property2');


?>