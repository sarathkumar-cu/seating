<?php
$id = 1;
$idoptions = 1;
require 'prime.php';

?>

<div class='desc'>
	<h3 id='hdesc'>Options Page</h3>
	<div id='cdesc'>
		 These are SETTINGS to change default options in home page.<br>
		<a class='error'>Warnings:</a><br>
		 Press `SAVE` button to 'apply' the settings.<br>
		 This page is loaded with default values. To get back default settings press 'SAVE' without any modifications.
	</div>
</div><?php /*
<center>
<font id='label' style='text-decoration:underline;'>Settings: (change default options in home page)</font>
</center> */ ?>
<form action='settings.php' method='POST'>
<center>
<?php /*
home page
---------
session		fn		an		both
			fn		an
incl pg no	yes		no
cur date	yes		no

Converter
---------
pdf to txt

*/?><table border='0'>
<tr>
<td><center>
	<label for='session' id='label'><br>Session</label><br><br><br><br><br><br>
	<label for='page' id='label'>Include Page No.</label>
</center></td>
<td><a id='label'><br>:<br><br><br><br><br><br>:</a>
</td>
<td style='border: 1px solid black; padding: 5px;'>
	<a class='descfont'><br>If file is in orignal format<br>
	<input type='radio' name='session' value='FN'>FN </input>
	<input type='radio' name='session' value='AN'>AN </input>
	<input type='radio' name='session' value='FNAN' checked>Both </input><br /><br />
	If the file is edited.<br /> Then, whole file as a single session<br>
	<input type='radio' name='session' value='editFN'>FN </input>
	<input type='radio' name='session' value='editAN'>AN </input><br><br>
	<input type='radio' name='page' value='page_yes'>Yes </input>
	<input type='radio' name='page' value='page_no' checked>No </input></a>
</td>
</tr><?php /*
<tr>
<td><label for='page' id='label'>Include Page No.</label></td>
<td><font id='label'>:</label></td>
<td style='border: 1px solid black; padding: 5px;'><a class='descfont'>
	<input type='radio' name='page' value='page_yes'>Yes </input>
	<input type='radio' name='page' value='page_no' checked>No </input>
</a></td>
</tr>*/ ?>
</table>
<input type="submit" name="submit" value="Save" class="button" id="submit">
</center>
</form><br>

<div id='pdf2text' class='guide'><h2 id='hdesc' class='detail'>Convert File</h2>
<center id='convert'>Convert a <b>PDF</b> to <b>Text</b> file.<br>
<form action='budge.php' method="post"
enctype="multipart/form-data">
<table>
<tr><?php /*
<td><center>
	<label for="file" id="label">File</label>
</center></td>
<td><a id='label'>:</a>
</td>*/?>
<td>
	<input type="file" name="file" class="button"><br>
</td>
</tr>
</table>
<input type="submit" name="submit" value="Convert" class="button" id="submit">
</form></center>
</div>


<?php
require 'downfall.php';
?>