<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>File Processing Station</title>
</head>
<body background="giphy.gif" bgcolor="#000000" style="background-size:cover; height:100%;">
<table height="100%" width="100%" style="height:100%; width:100%;"><tr><td align="center" valign="middle">

<?
// If there's a file, check for an error.
if ($_FILES["file"]["error"] > 0) { echo "Error: " . $_FILES["file"]["error"] . "<br>"; }

// If there's a file and no error, then process it.
else if(isset($_FILES["file"]))
{
		
	// Replacement list for text and html.
	$replace = array(
		'–' => '-',
		'”' => '"',
		'“' => '"',
		"‘" => "'",
		"’" => "'",
		'–' => '-',	
		);
	
	// List for text only.
	if ( strpos($_FILES['file']['name'], '.txt') )
		{
			$replace['®'] = '(R)';
			$replace['©'] = '(C)';
	}
	
	// List for html only.
	else if ( strpos($_FILES['file']['name'], '.html') )
		{
			$replace['®'] = '&reg;';
			$replace['©'] = '&copy;';
			$replace['•'] = '&bull;';
			$replace[' & '] = ' &amp; ';
			$replace[' >'] = '>';
	}
	
	
	// Make replacements and save file.		
	$file_contents = file_get_contents($_FILES['file']['tmp_name']);
	$original = $file_contents;
	foreach ($replace as $old => $new) { $file_contents = str_replace($old,$new,$file_contents); }
	file_put_contents($_FILES["file"]["name"],$file_contents);

	// Create link to updated file.
	echo '<table height="120" width="400" bgcolor="#FFFFFF"><tr><td align="center" valign="middle">';
	echo "Processed: <a href='". $_FILES["file"]["name"] ."'>". $_FILES["file"]["name"] ."</a>";
	if ($original != $file_contents) { echo "<p style='color:#900;'>Updated</p>"; }
	else { echo "<p style='color:#999;'>No Changes Made</p>"; }
	echo "</td></tr></table><br />";	
}


// Form for uploading file.
?>
<form action="index.php" method="post" enctype="multipart/form-data">
<table height="120" width="400" bgcolor="#FFFFFF"><tr><td align="center" valign="middle">

<table cellpadding="15">
<tr><th colspan="2">Please Upload Text or HTML</th></tr>
<tr>
  
    <td align="center" width="200"><input type="file" name="file" id="file"></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="submit" value="Upload"></td></tr>
</table>
</form>


</td></tr></table>

</td></tr></table>
</body>
</html>