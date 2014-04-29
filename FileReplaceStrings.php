<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>File Processing Station</title>
<style>
div.box{
	width:400px;
	min-height:100px;
	background-color:#ffffff;
	margin-top: 30px;
	padding-top: 20px;
	padding-bottom: 20px;
	text-align:center;
}
</style>
</head>
<body background="giphy.gif" bgcolor="#000000" style="background-size:cover; height:100%;">
<div style="width:400px; margin-left:auto; margin-right:auto;">
<?

for($i=0; $i<count($_FILES['upload']['name']); $i++) {
	
			
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
	if ( strpos($_FILES['upload']['name'][$i], '.txt') )
		{
			$replace['®'] = '(R)';
			$replace['©'] = '(C)';
	}
	// List for html only.
	else if ( strpos($_FILES['upload']['name'][$i], '.html') )
		{
			$replace['®'] = '&reg;';
			$replace['©'] = '&copy;';
			$replace['•'] = '&bull;';
			$replace[' & '] = ' &amp; ';
			$replace[' >'] = '>';
	}
	
	
	// Make replacements and save file.		
	$file_contents = file_get_contents($_FILES['upload']['tmp_name'][$i]);
	$original = $file_contents;
	foreach ($replace as $old => $new) { $file_contents = str_replace($old,$new,$file_contents); }
	file_put_contents($_FILES["upload"]["name"][$i],$file_contents);

	// Create link to updated file.
	echo '<div class="box">';
	echo "<p align='center'>Processed: <a href='". $_FILES["upload"]["name"][$i] ."'>". $_FILES["upload"]["name"][$i] ."</a></p>";
	if ($original != $file_contents) { echo "<p style='color:#900;'>Updated</p>"; }
	else { echo "<p style='color:#999;'>No Changes Made</p>"; }
	echo "</div>";	
}

// Form for uploading file.
?>

<div class="box">
<p align="center">
<form action="index.php" method="post" enctype="multipart/form-data">
Please Upload Text or HTML<br /><br />
<input type="file" name="upload[]"  multiple="multiple"><br><br />
<input type="submit" name="submit" value="Upload">
</form>
</p>
</div>


</div>
</body>
</html>