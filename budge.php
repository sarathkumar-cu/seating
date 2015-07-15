<?php
$id = 1;
require 'prime.php';
$myFile = "upload//file.txt";

if(!isset($_FILES["file"]["name"]))
	header("Location: blunder.php");
else
$temp = explode(".", $_FILES["file"]["name"]);

if ( isset($_FILES["file"]) ){
if ($_FILES["file"]["type"] == "application/pdf" || $_FILES["file"]["type"] == "text/plain")
{
	if ($_FILES["file"]["error"] > 0)
	{
		echo "<h3 id='hdesc' class='error'>Error</h3>Return Code: " . $_FILES["file"]["error"] . "<br>";
		header("Location: blunder.php");
	}
	else
	{

	  if($_FILES["file"]["type"] == "text/plain")
	  {
		move_uploaded_file($_FILES["file"]["tmp_name"],"upload/file.txt");
	  }
	  else
	  {
		move_uploaded_file($_FILES["file"]["tmp_name"],"upload/file.pdf");
		system('pdftotext upload/file.pdf');
      }
	}
}
else
header("Location: blunder.php");
}
?>

<div class='desc'>
	<h3 id='hdesc'>Text Conversion</h3>
	<div id='cdesc'>
		<a class='error'>Warnings:</a><br>
		 'SAVE FILE AS...' button saves only unmodified text conversion.<br>
		 Doing so, modifications made in text box below, WILL NOT BE SAVED into the text file.<br>
		 If modifications are needed to be used, copy it to a new file.
	</div>
</div>

<div id='pdf2text'>
<center><h2 id='hdesc' class='detail'>Converted File</h2>
<form action='options.php#convert' method='POST'>
<a class="button" id="submit" href="upload/file.txt" style="color: #000;">Right click here & SAVE FILE AS...</a>
<br><br><br>

<textarea id="textarea" rows=10 cols=80 style="resize: vertical;
max-height: 300px;
padding:5px;
border-radius:5px;
font-family: monospace;
background:#FFFEFE;
margin-top: 10px;
cursor: pointer;
border:2px solid #000;">
<?php
$fh = fopen($myFile, 'r');
//$theData = fread($fh, 5);
//$theData = fread($fh, filesize($myFile));
$line = "";
rewind($fh);
while(!feof($fh)){
	$line .= "".fgets($fh);
}
fclose($fh);
echo $line;
//echo "".$theData;
//echo $_FILES["file"]["name"];
?>
</textarea><br>
<input type="submit" name="submit" value="Go back to 'Options' page" class="button" id="submit">
</form></center>
</div>

<?php
require 'downfall.php';
?>