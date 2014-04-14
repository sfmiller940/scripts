<?

/*
This PHP Script replaces strings in a file
according to a choice of replacement schema (text or html). 
*/

// Replacement list for text and html.
$replace = array(
	'–' => '-',
	'”' => '"',
	'“' => '"',
	"‘" => "'",
	"’" => "'",
	'–' => '-',	
	);

// Additional replacements for text.
if ($_POST['filetype']=='text')
	{
		$replace['®'] = '(R)';
		$replace['©'] = '(C)';
}

// Additional replacements for html.
else
	{
		$replace['®'] = '&reg;';
		$replace['©'] = '&copy;';
		$replace['•'] = '&bull;';
		$replace[' & '] = ' &amp; ';
		$replace[' >'] = '>';
}


// Barebones HTML.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>File Processing Station</title>
</head>
<body background="giphy.gif" bgcolor="#000000" style="background-size:cover; height:100%;">
<table height="100%" width="100%" style="height:100%; width:100%;"><tr><td align="center" valign="middle"><table height="120" width="400" bgcolor="#FFFFFF"><tr><td align="center" valign="middle">

<?

// If there's no file, then display form to upload file.
if ( !isset( $_FILES["file"] ) )

	{ 
	
	?>
	
	<form action="index.php" method="post" enctype="multipart/form-data">
	<table cellpadding="15">
    <tr><th colspan="2">Please Upload</th></tr>
    <tr>
        <td width="200"><label><input type="radio" name="filetype" value="text" checked="checked">Text</label><br /><label><input type="radio" name="filetype" value="html" STYLE="FLOAT:left;">HTML</label></td>
        <td align="center" width="200"><input type="file" name="file" id="file"></td>
    </tr>
    <tr><td align="center" colspan="2"><input type="submit" name="submit" value="Upload"></td></tr>
    </table>
	</form>
	<?
}

// If there's a file, check for an error.
else if ($_FILES["file"]["error"] > 0) { echo "Error: " . $_FILES["file"]["error"] . "<br>"; }

// If there's a file and no error, then replace, save and display.
else{
	
	$file_contents = file_get_contents($_FILES['file']['tmp_name']);
	foreach ($replace as $old => $new) { $file_contents = str_replace($old,$new,$file_contents); }
	file_put_contents($_FILES["file"]["name"],$file_contents);
	echo "Processed: <a href='". $_FILES["file"]["name"] ."'>". $_FILES["file"]["name"] ."</a>";
}
?>

</td></tr></table>
</td></tr></table>
</body>
</html>