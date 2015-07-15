<?php

	/*<html>
<head>
<title>Anand Institute of Higher Technology</title>
</head>
<body>
session_start();
    system('pdftotext sample.pdf');
	system('touch test.txt');

$mytxt = "e:\\Project\\003.txt";
$_SESSION['urltxt'] = $mytxt;
/*<a href="try07main.php">Arrange
</a>
</body>
</html>


$path = 'C:/xampp/htdocs/tutorials/';
$dir      = opendir($path);
$filename = array();/*
$dir = opendir($path);
$f = readdir($dir);
while ($filename = readdir($dir)) {
if (eregi("\.pdf",$filename)){
    $content  = shell_exec('C:/xampp/htdocs/tutorials/pdftotext '.$filename.' ');
    $read     = strtok ($filename,".");
    $testfile = "$read.txt";
    $file     = fopen($testfile,"r");
    if (filesize($testfile)==0){} 
    else{
        $text = fread($file,filesize($testfile));
        fclose($file);
        echo "</br>"; echo "</br>";
    }
}
}*/
//system(paste('"C:/Program Files/XPDF/pdftotext.exe"', '"C:/Users/Sarath/Desktop/cuSK/t03/sample.pdf"'));
//***************system('pdftotext test.pdf')//xpdf in prog files// changing environment variables// pasting pdftotext here

//session_start();
$id = 1;
require 'prime.php';
require 'settings//settings.php';

if(isset($settings_session))
	$a1 = $settings_session;
else
	$a1 = 2;
if(isset($settings_page))
	$a2 = $settings_page;
else
	$a2 = 1;
?>
<!--
*. prime.php
#. downfall.php
&. allure.css
1. index.php		-> (*) (#) (&)
2. fileprocess.php
3. pick.php  		-> (*) (#) (&) (2)
4. bis_pick.php 	-> (*) (#) (&) (3) (5)
5. authenticate.php	-> (*) (#) (&)
6. hustling.php		-> (*) (#) (&)
7. spruce.php		-> (*) (#) (&)
8. aquisitio.php	-> (2) (9)
9. fpdf.php			-> (10)
10.fpdf.css
11.blunder.php		-> (*) (#) (&)

steps:
upload	halls	confirmation	pdf
-->
<div class='desc'>
	<h3 id='hdesc'>Upload PDF or Text file</h3>
	<div id='cdesc'>
		First step is to upload the PDF or the Text file containing Register Numbers.
		File will be validated showing details on next step.
		In a successful next step you will be asked to select exam halls.
	</div>
</div>
<form action="pick.php#label" method="post"
enctype="multipart/form-data">
<center>
<table border='0'>
<tr>
<td><center>
	<label for="file" id="label">File</label>
</center></td>
<td><a id='label'>:</a>
</td>
<td>
	<input type="file" name="file" class="button"><br>
</td>
</tr>
<tr>
<td><center>
	<label for='session' id='label'>Session</label><br><br><br><br><br>
</center></td>
<td><a id='label'>:</a><br><br><br><br><br>
</td>
<td style='border: 1px solid black; padding: 5px;'>
	<a class='descfont'><br>If file is in orignal format<br>
	<input type='radio' name='session' value='FN'<?php if($a1 == 0) echo ' checked';?>>FN </input>
	<input type='radio' name='session' value='AN'<?php if($a1 == 1) echo ' checked';?>>AN </input>
	<input type='radio' name='session' value='FNAN'<?php if($a1 == 2) echo ' checked';?>>Both </input><br /><br />
	If the file is edited.<br /> Then, whole file as a single session<br>
	<input type='radio' name='session' value='editFN'<?php if($a1 == 3) echo ' checked';?>>FN </input>
	<input type='radio' name='session' value='editAN'<?php if($a1 == 4) echo ' checked';?>>AN </input></a><br><br>
	<input type='checkbox' name='page_number'<?php if($a2 == 0) echo ' checked';?>><a class='descfont'> <i>Include Page Number</i></a></input>
</td>
</tr>
<tr>
<td><center>
	<label for='date' id='label' class='dateselect'>Exam-on</label>
</center></td>
<td><a id='label'>:</a>
</td>
<td>
	<select name='day' class='descfont'>
	<option value='01'<?php if (date('d')=='01') echo 'selected'; ?>>01</option>
	<option value='02'<?php if (date('d')=='02') echo 'selected'; ?>>02</option>
	<option value='03'<?php if (date('d')=='03') echo 'selected'; ?>>03</option>
	<option value='04'<?php if (date('d')=='04') echo 'selected'; ?>>04</option>
	<option value='05'<?php if (date('d')=='05') echo 'selected'; ?>>05</option>
	<option value='06'<?php if (date('d')=='06') echo 'selected'; ?>>06</option>
	<option value='07'<?php if (date('d')=='07') echo 'selected'; ?>>07</option>
	<option value='08'<?php if (date('d')=='08') echo 'selected'; ?>>08</option>
	<option value='09'<?php if (date('d')=='09') echo 'selected'; ?>>09</option>
	<option value='10'<?php if (date('d')=='10') echo 'selected'; ?>>10</option>
	<option value='11'<?php if (date('d')=='11') echo 'selected'; ?>>11</option>
	<option value='12'<?php if (date('d')=='12') echo 'selected'; ?>>12</option>
	<option value='13'<?php if (date('d')=='13') echo 'selected'; ?>>13</option>
	<option value='14'<?php if (date('d')=='14') echo 'selected'; ?>>14</option>
	<option value='15'<?php if (date('d')=='15') echo 'selected'; ?>>15</option>
	<option value='16'<?php if (date('d')=='16') echo 'selected'; ?>>16</option>
	<option value='17'<?php if (date('d')=='17') echo 'selected'; ?>>17</option>
	<option value='18'<?php if (date('d')=='18') echo 'selected'; ?>>18</option>
	<option value='19'<?php if (date('d')=='19') echo 'selected'; ?>>19</option>
	<option value='20'<?php if (date('d')=='20') echo 'selected'; ?>>20</option>
	<option value='21'<?php if (date('d')=='21') echo 'selected'; ?>>21</option>
	<option value='22'<?php if (date('d')=='22') echo 'selected'; ?>>22</option>
	<option value='23'<?php if (date('d')=='23') echo 'selected'; ?>>23</option>
	<option value='24'<?php if (date('d')=='24') echo 'selected'; ?>>24</option>
	<option value='25'<?php if (date('d')=='25') echo 'selected'; ?>>25</option>
	<option value='26'<?php if (date('d')=='26') echo 'selected'; ?>>26</option>
	<option value='27'<?php if (date('d')=='27') echo 'selected'; ?>>27</option>
	<option value='28'<?php if (date('d')=='28') echo 'selected'; ?>>28</option>
	<option value='29'<?php if (date('d')=='29') echo 'selected'; ?>>29</option>
	<option value='30'<?php if (date('d')=='30') echo 'selected'; ?>>30</option>
	<option value='31'<?php if (date('d')=='31') echo 'selected'; ?>>31</option>
	</select>
	<select name='month' class='descfont'>
	<option value='Jan'<?php if (date('M')=='Jan') echo 'selected'; ?>>Jan</option>
	<option value='Feb'<?php if (date('M')=='Feb') echo 'selected'; ?>>Feb</option>
	<option value='Mar'<?php if (date('M')=='Mar') echo 'selected'; ?>>Mar</option>
	<option value='Apr'<?php if (date('M')=='Apr') echo 'selected'; ?>>Apr</option>
	<option value='May'<?php if (date('M')=='May') echo 'selected'; ?>>May</option>
	<option value='Jun'<?php if (date('M')=='Jun') echo 'selected'; ?>>Jun</option>
	<option value='Jul'<?php if (date('M')=='Jul') echo 'selected'; ?>>Jul</option>
	<option value='Aug'<?php if (date('M')=='Aug') echo 'selected'; ?>>Aug</option>
	<option value='Sep'<?php if (date('M')=='Sep') echo 'selected'; ?>>Sep</option>
	<option value='Oct'<?php if (date('M')=='Oct') echo 'selected'; ?>>Oct</option>
	<option value='Nov'<?php if (date('M')=='Nov') echo 'selected'; ?>>Nov</option>
	<option value='Dec'<?php if (date('M')=='Dec') echo 'selected'; ?>>Dec</option>
	</select>
	<select name='year' class='descfont'>
	<?php $shwet = date('Y'); ?>
	<option value='<?php echo($shwet-2); ?>'><?php echo ($shwet-2);?></option>
	<option value='<?php echo($shwet-1); ?>'><?php echo ($shwet-1);?></option>
	<option value='<?php echo $shwet; ?>' selected><?php echo $shwet;?></option>
	<option value='<?php echo($shwet+1); ?>'><?php echo ($shwet+1);?></option>
	<option value='<?php echo($shwet+2); ?>'><?php echo ($shwet+2);?></option>
	</select><br />
</td>
</tr>
</table>
	<input type="submit" name="submit" value="Submit" class="button" id="submit">
</center>
</form>
<?php require 'downfall.php'; ?>