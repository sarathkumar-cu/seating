<?php
//PICK			PICK				PICK			PICK			PICK			PICK
if(!isset($_FILES["file"]["name"]))
	header("Location: blunder.php");
//session_start();
$allowedExts = array("PDF", "pdf");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$back = 0;
$id = 2;

$pickfile = "settings//pick.php";
$authfile = "settings//authenticate.php";

$jscriptfn = 0;
$jscriptan = 0;

$mm;
require 'prime.php';
echo "<div class='desc'>";
/*------------------------------------------------------------*/
/*--- HERE STARTS DATE AND SESSION STORAGE INTO A VARIABLE ---*/

if ( isset($_POST['month']) && isset($_POST['day']) && isset($_POST['year']) )
{
	$datePrint = $_POST['month']." ".$_POST['day'].", ".$_POST['year'];
	//$_SESSION['datePrint'] = $datePrint;
	if($_POST['month'] == 'Jan')
	{	$mm = '01';	}
	elseif($_POST['month'] == 'Feb')
	{	$mm = '02';	}
	elseif($_POST['month'] == 'Mar')
	{	$mm = '03';	}
	elseif($_POST['month'] == 'Apr')
	{	$mm = '04';	}
	elseif($_POST['month'] == 'May')
	{	$mm = '05';	}
	elseif($_POST['month'] == 'Jun')
	{	$mm = '06';	}
	elseif($_POST['month'] == 'Jul')
	{	$mm = '07';	}
	elseif($_POST['month'] == 'Aug')
	{	$mm = '08';	}
	elseif($_POST['month'] == 'Sep')
	{	$mm = '09';	}
	elseif($_POST['month'] == 'Oct')
	{	$mm = '10';	}
	elseif($_POST['month'] == 'Nov')
	{	$mm = '11';	}
	elseif($_POST['month'] == 'Dec')
	{	$mm = '12';	}
	//$_SESSION['dateReal'] = $_POST['day'].".".$mm.".".$_POST['year'];
	//$dateReal = $_SESSION['dateReal'];
	$dateReal = $_POST['day'].".".$mm.".".$_POST['year'];
	//$_SESSION['pdfDate'] = "Seatings ".$_POST['year'].".".$mm.".".$_POST['day'].".pdf";
	$pdfDate = "Seatings ".$_POST['year'].".".$mm.".".$_POST['day'].".pdf";
}

if (isset($_POST['page_number']))
	$page_number = 1;
else
	$page_number = 0;

if (isset($_POST['session']))
{
if ($_POST['session'] == 'editFN')
{
	$sessionPrint = 'FN';
	//$SESSION['edited'] = 'FN';
	$sessionEdited = 'FN';
}
elseif ($_POST['session'] == 'editAN')
{
	$sessionPrint = 'AN';
	//$SESSION['edited'] = 'AN';
	$sessionEdited = 'AN';
}
elseif ($_POST['session'] != 'FNAN')
	$sessionPrint = $_POST['session'];
else
	$sessionPrint = 'FN and AN';
//$_SESSION['sessionPrint'] = $sessionPrint;
if ($_POST['session'] == 'editFN')
	$sessionReal = 'FN';//$_SESSION['sessionReal'] = 'FN';
elseif ($_POST['session'] == 'editAN')
	$sessionReal = 'FN';//$_SESSION['sessionReal'] = 'AN';
else
	$sessionReal = $_POST['session'];//$_SESSION['sessionReal'] = $_POST['session'];
//$sessionReal = $_SESSION['sessionReal'];
}

/*----------------------------------------------------------*/
/*--- HERE ENDS DATE AND SESSION STORAGE INTO A VARIABLE ---*/
/*----------------------------------------------------------*/



if ($_FILES["file"]["type"] == "application/pdf" || $_FILES["file"]["type"] == "text/plain")
{
	if ($_FILES["file"]["error"] > 0)
	{
		echo "<h3 id='hdesc' class='error'>Error</h3>Return Code: " . $_FILES["file"]["error"] . "<br>";
		$back = 1;
	}
	else
	{
		echo "<h1 id='hdesc' class='detail'>Details</h1>Uploaded file: \"" . $_FILES["file"]["name"] . "\"<br>";//$_FILES["file"]["name"] filename
		echo "Type: " . $_FILES["file"]["type"] . "<br>";//$_FILES["file"]["type"] file type
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";//$_FILES["file"]["size"] size
		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";//$_FILES["file"]["tmp_name] temporary file (full [server] file path)

    /*if (file_exists("upload/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {*/
	  if($_FILES["file"]["type"] == "text/plain")
	  {
		move_uploaded_file($_FILES["file"]["tmp_name"],"upload/file.txt");
		echo "Uploaded to: " . "server/upload/file.txt";
	  }
	  else
	  {
		//move_uploaded_file($_FILES["file"]["tmp_name"],"upload/file.pdf" /*. $_FILES["file"]["name"]*/);
		move_uploaded_file($_FILES["file"]["tmp_name"],"upload/file.pdf");
		echo "Uploaded to: " . "server/upload/file.pdf"/* . $_FILES["file"]["name"]*/;
		//system('pdftotext upload/file.pdf');
		system('pdftotext upload/file.pdf');
      }
	  ///}
	  echo "<br>Note: <a class='error'>An extra hall</a> is asked for avoiding an error during final-arrangement process.<br>";
	}
	require 'fileprocess.php';
	if($_POST['session'] != 'editAN' && $_POST['session'] != 'editFN')
	{
	if($sessionReal == 'FNAN')
	{
	mainarranger('FN');
	$tempfnan = $ttlb; $ttlbFN = $ttlb;
	mainarranger('AN');
	$tempanfn = $ttlb; $ttlbAN = $ttlb;
	if ($tempfnan == 0 && $tempanfn == 0)
	{
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3>Yes, it is a PDF or Text file.";
		echo "<br>But, it has some <b>problems</b>.";
		//echo "<br>AN = ".$ttlbAN." FN = ".$ttlbFN;
		echo "<br><b>It may not contain any register numbers in it.</b>";
		echo "<br><b>Or, the file is not in original format.</b><br>";
		echo "<br>'Go back' and try uploading a valid PDF or Text file sent from Anna University.";
		echo " Or the file containing only Register Numbers in it.";
		$back = 1;
	}
	}
	elseif($sessionReal == 'FN')
	{
	mainarranger('FN');
	$ttlbFN = $ttlb;
	if ($ttlb == 0)
	{
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3>Yes, it is a PDF or Text file.";
		echo "<br>But, <b>it does not contain any register numbers</b> in it.";
		echo "<br>Or may be, <b>it does not contain any forenoon session register numbers</b> in it.";
		echo "<br>'Go back' and try uploading a valid PDF or Text file sent from Anna University.";
		echo " Or the file containing only Register Numbers in it.";
		$back = 1;
	}
	}
	elseif($sessionReal == 'AN')
	{
	mainarranger('AN');
	$ttlbAN = $ttlb;
	if ($ttlb == 0)
	{
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3>Yes, it is a PDF or Text file.";
		echo "<br>But, <b>it does not contain any register numbers</b> in it.";
		echo "<br>Or may be, <b>it does not contain any afternoon session register numbers</b> in it.";
		echo "<br>'Go back' and try uploading a valid PDF or Text file sent from Anna University.";
		echo " Or the file containing only Register Numbers in it.";
		$back = 1;
	}
	}
	}
	else
	{
	mainarranger('none');
	$mainarranger = 'none';
	$ttlbNone = $ttlb;
	if ($ttlb == 0)
	{
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3>Yes, it is a PDF or Text file.";
		echo "<br>But, <b>it does not contain any register numbers</b> in it.";
		echo "<br>'Go back' and try uploading a valid PDF or Text file sent from Anna University.";
		echo " Or the file containing only Register Numbers in it.";
		$back = 1;
	}
	}
}
else
{
	echo "<h3 id='hdesc' class='error'>Error</h3>Invalid! Go back and please choose a PDF or Text file.";
	$back = 1;
}
if($back == 1)
{
	echo "</div>\n<form action='../seating' method='POST'>\n	<center><input type='submit' value='Go Back!' class='button'></center>\n</form>";
}
unset($ttlb);
if($back == 0)
{
	if($_POST['session'] != 'editAN' && $_POST['session'] != 'editFN')
	{
	if($sessionReal == 'FNAN')
	{
	//mainarranger('FN');
	$calc_rooms = intval($ttlbFN / 25) + 1;
	if($ttlbFN % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	//$_SESSION['noh'] = $calc_rooms;
	$sessionNoh = $calc_rooms;
	if($calc_rooms == 1)
	{
		//$_SESSION['only'] = 'AN';
		$sessionOnly = 'AN';
		$sessionReal = 'AN';
		//$_SESSION['sessionReal'] = 'AN';
		$sessionPrint = 'AN';
		//$_SESSION['sessionPrint'] = 'AN';
		$jscriptan = 1;
		goto onlyAN;
	}
	echo"<br>\nTotal Number of Students (FN): ".$ttlbFN."<br>\nTotal Number of Exam Halls required (FN): <b>".$calc_rooms."</b>\n";
	//mainarranger('AN');
	$calc_rooms = intval($ttlbAN / 25) + 1;
	if($ttlbAN % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	//$_SESSION['noh2'] = $calc_rooms;
	$sessionNoh2 = $calc_rooms;
	if($calc_rooms == 1)
	{
		//$_SESSION['only'] = 'FN';
		$sessionOnly = 'FN';
		$sessionReal = 'FN';
		//$_SESSION['sessionReal'] = 'FN';
		$sessionPrint = 'FN';
		//$_SESSION['sessionPrint'] = 'FN';
		$jscriptfn = 1;
		goto onlyFN;
	}
	echo"<br>\nTotal Number of Students (AN): ".$ttlbAN."<br>\nTotal Number of Exam Halls required (AN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n";
	echo"<label for='hall' id='label'>Select Exam-halls for FN</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	echo"<label for='hall2' id='label'>Select Exam-halls for AN</label>\n\t<input type='text' name='hall2' size=30 id='input2'><br>\n";
	}
	elseif($sessionReal == 'FN')
	{	
	//mainarranger('FN');
	$calc_rooms = intval($ttlbFN / 25) + 1;
	if($ttlbFN % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	//$_SESSION['noh'] = $calc_rooms;
	$sessionNoh = $calc_rooms;
	echo"<br>\nTotal Number of Students (FN): ".$ttlbFN."<br>\nTotal Number of Exam Halls required (FN): <b>".$calc_rooms."</b>";
	$jscriptfn = 1;
	onlyFN:
	if(isset($sessionOnly) && ($sessionOnly == 'FN'))
		echo"<br><br><div class='error'>Please note that afternoon (AN) session is not included.</div>Because, there are no register numbers found in that PDF or Text file for afternoon (AN) session.";
	echo"</div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Select Exam-halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
	elseif($sessionReal == 'AN')
	{
	$jscriptan = 1;
	onlyAN:
	//mainarranger('AN');
	$calc_rooms = intval($ttlbAN / 25) + 1;
	if($ttlbAN % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	//$_SESSION['noh'] = $calc_rooms;
	$sessionNoh = $calc_rooms;
	echo"<br>\nTotal Number of Students (AN): ".$ttlbAN."<br>\nTotal Number of Exam Halls required (AN): <b>".$calc_rooms."</b>";
	if(isset($sessionOnly) && ($sessionOnly == 'AN'))
		echo"<br><br><div class='error'>Please note that forenoon (FN) session is not included.</div>Because, there are no register numbers found in that PDF or Text file for forenoon (FN) session.";
	if(isset($sessionOnly))
		unset($sessionOnly);
	echo"</div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Select Exam-halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
	}
	else
	{
	//mainarranger('none');
	$jscriptan = 1;
	$calc_rooms = intval($ttlbNone / 25) + 1;
	if($ttlbNone % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	//$_SESSION['noh'] = $calc_rooms;
	$sessionNoh = $calc_rooms;
	echo"<br>\nTotal Number of Students (".$sessionReal."): ".$ttlbNone."<br>\nTotal Number of Exam Halls required (".$sessionReal."): <b>".$calc_rooms."</b>";
	echo"</div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Select exam-halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
	echo "<input id='submit7' type='submit' value='Check' class='button' /></form></center><br>";
echo "<div id='examhallsdisplay' class='guidea'><h2 id='hdesc' class='detail'>Exam-Halls</h2>";
echo "Typed Exam Halls are: <br>";
if($sessionReal != 'FNAN')
	echo "For <b>".$sessionReal."</b> session: ";
else
	echo "For <b>FN</b> session:";
	
echo "<br>Required <b>".$sessionNoh."</b> ";
if($sessionNoh > 1)
echo "halls";
else
echo "hall";
echo" and you are typing <b><span id='javatotal1'>1st</span> </b>hall
<br>Halls: <span id='javascripter1'><i><< appears when typed >></i>
</span>";

if($sessionReal == 'FNAN'){
echo "<br><br>For <b>AN</b> session:
<br>Required <b>".$sessionNoh2."</b> ";
if($sessionNoh2 > 1)
echo "halls";
else
echo "hall";
echo" and you are typing <b><span id='javatotal2'>1st</span> </b>hall
<br>Halls: <span id='javascripter2'><i><< appears when typed >></i>
</span>";}

echo "<br><br><i id='pick_predict'>(these predictions are not always correct)</i>
</div>";
	echo"<br><div class='guide'><h2 id='hdesc' class='detail'>Guidelines</h2>";
	echo"These are the rules for selecting the exam halls.<br><b class='correct'>Do's:</b><br>";
	echo"<ul><li>To select range, say from D1 to D20 type <b class='correct'>D1 - D20</b>.</li>";
	echo"<li>To select individuals, type <b class='correct'>C2, C6, A1</b>.</li>";
	echo"<li><b>Example</b>: A1 - A5, A7, D1 - D5, C1</li>";
	echo"<li>Number of Halls required must be satisfied. If 40 exam halls are required, then atleast 40 halls must be chosen. ";
	echo"Can select more than that which will be filter off in next step.<br><br><b class='error'>Dont's:</b></li>";
	echo"<li>Only use above mentioned correct format. Any words like 'and', 'or', 'to', etc. causes error.</li>";
	echo"<li>Halls must be alpha-numberic and no spaces in between. <b class='error'>A 2, 3C, A</b> - Wrong</li>";
	echo"<li>Range must be of same alphabet, say if you want A1 to A20 and B1 to B6. </li>";
	echo"Correct way is <b class='correct'>A1 - A20, B1 - B6</b>. But, <b class='error'>A1 - B6</b> is Wrong.</div>";
}
?>
<script type="text/javascript">
"use strict";
document.addEventListener('DOMContentLoaded', function(){
	document.getElementById('examhallsdisplay').className = 'guide';
});
document.forms[0].addEventListener('submit', Check);//for submit event
function Check(event) {/*
<?php
if($jscriptfn == 1 || $jscriptan == 1)
	echo "cont2 = 1;
";
?>*/
	if(cont1 == 1 && cont2 == 1)
	{
	//event.preventDefault();
	//console.log('You can continue safely. c1 = '+cont1+', c2 = '+cont2+'.');
	}
	else
	{
	/*
		estimateHalls2(event);
		estimateHalls(event);//now working here
		if(cont1 == 1 && cont2 == 1)
		{
		//event.preventDefault();
		console.log('You just got here safely. c1 = '+cont1+', c2 = '+cont2+'.');
		} else */
	{ 
		event.preventDefault();
		console.log('Required number of halls must be filled c1 = '+cont1+', c2 = '+cont2+'.');
		var alerter;
		if(cont1 != 1 && cont2 != 1)
			alerter = 'Check the total number of halls for both sessions';
		else if(cont1 != 1){
			if(document.getElementById('input2') == null)
				alerter = 'Check the total number of halls';
			else
				alerter = 'Check the total number of halls for forenoon session';
		}
		else if(cont2 != 1)
			alerter = 'Check the total number of halls for afternoon session';
		alerter = alerter + '\nRequired number of halls must be filled';
		alert(alerter);}
	}
}
var cont1, cont2;
var recheck = 0;
/*
function pick_predict(){
if(cont1 == 1 && cont2 == 1)
	document.getElementById('pick_predict').className = 'error';
else
	document.getElementById('pick_predict').className = 'cooling';
}*/


document.getElementById('input').addEventListener('keyup', estimateHalls);//for halls
document.getElementById('submit7').addEventListener('mouseover', estimateHalls);
function estimateHalls(event){
	event.preventDefault();
	//pick_predict();
	var temp ;//= document.forms[0].elements[0].value.toUpperCase();
	//document.forms[0].elements[0].value = temp;
	var hall_1 = document.getElementById('input').value;
	var is_comma = hall_1[(hall_1.length - 1)];
	//console.log(is_comma);
	if(is_comma != ',' && is_comma != ' ')
		hall_1 = hall_1 + ',';
	//console.log(hall_1);
	var session1 <?php echo '= '.$sessionNoh; ?>;
	session1 = parseInt(session1);
	//console.log(session2);
	var hall_1_nocomma = hall_1.split(',');
	var halls_1 = [];
	var j = 0;
	var tempnumb;
	var tempnum = [];
	var tempalpha;
	for(var i = 0; i < hall_1_nocomma.length; i++)
	{
		// Trim the excess whitespace.
		hall_1_nocomma[i] = hall_1_nocomma[i].replace(/^\s*/, "").replace(/\s*$/, "");
		
		if(hall_1_nocomma[i].indexOf('-') === -1)
		{
			tempnumb = hall_1_nocomma[i].replace(/[^0-9]+/ig,"");//number
			tempalpha = hall_1_nocomma[i].replace(/[^a-z]/gi, "");//alphabet
			halls_1[j] = tempalpha + tempnumb;
			j = j + 1;
		}
		else
		{
			temp = hall_1_nocomma[i].split('-');
			for(var l = 0; l < l.length; l++)
				temp[l] = temp[l].replace(/^\s*/, "").replace(/\s*$/, "");
			//console.log(temp);
			tempnum[0] = parseInt(temp[0].replace(/[^0-9]+/ig,""), 10);//getting numeric first value
			tempalpha = temp[0].replace(/[^a-z]/gi, "");//getting alphabetic first value
			if(typeof temp[1] != 'undefined')
			{
			tempnum[1] = parseInt(temp[1].replace(/[^0-9]+/ig,""), 10);//getting numeric second value
			if(tempnum[0] <= tempnum[1]){
			for(var k = tempnum[0]; k <= tempnum[1]; k++)
			{
				halls_1[j] = tempalpha+ "" + "" + k;
				j = j + 1;
			}
			}
			}
		}
	}
	//console.log(halls_1);
	var final1 = '<b>';
	for(var i = 0; i < halls_1.length; i++){
		halls_1[i] = halls_1[i].toUpperCase();
	}
	for(var i = 0; i < halls_1.length; i++){
		final1 += halls_1[i];
		if( (i+1) != halls_1.length )
			final1 += ', ';
	}
	if (hall_1 == ''){
		document.getElementById('javascripter1').innerHTML = "<i><< appears when typed >></i>";
		document.getElementById('javatotal1').innerHTML = '1st';
		document.getElementById('javascripter1').className = 'cooling';
		cont1 = 0;
	}else{
		var k1 = halls_1.length - 1;
		if(k1 >= session1)//to change color at right time
		{
			document.getElementById('javascripter1').className = 'correct';//fulfil';
			//global save situation
			cont1 = 1;
		}
		else{
			document.getElementById('javascripter1').className = 'error';
			cont1 = 0;
		}
		document.getElementById('javascripter1').innerHTML = '<b>' + final1 + '</b>';
		if((k1%10 == 1))
			document.getElementById('javatotal1').innerHTML = k1 + 'st';
		else if((k1%10 == 2))
			document.getElementById('javatotal1').innerHTML = k1 + 'nd';
		else if((k1%10 == 3))
			document.getElementById('javatotal1').innerHTML = k1 + 'rd';
		else
			document.getElementById('javatotal1').innerHTML = k1 + 'th';
	}
	
	// below one is for the second session.
	//if ()
}

if(document.getElementById('input2') != null) {
	document.getElementById('input2').addEventListener('keyup', estimateHalls2);//for halls2
	document.getElementById('submit7').addEventListener('mouseover', estimateHalls2);
	console.log('Second input is detected :)');
} else {
	console.log('No second input :)');
	cont2 = 1;
}
function estimateHalls2(event){
	event.preventDefault();
	//pick_predict();
	var temp ;//= document.forms[0].elements[0].value.toUpperCase();
	//document.forms[0].elements[0].value = temp;
	var hall_2 = document.getElementById('input2').value;
	var is_comma2 = hall_2[(hall_2.length - 1)];
	//console.log(is_comma);
	if(is_comma2 != ',' && is_comma2 != ' ')
		hall_2 = hall_2 + ',';
	var session2 <?php if (isset($sessionNoh2))
	echo "= ".$sessionNoh2.";
	session2 = parseInt(session2);";?>
	//console.log(session2);
	var hall_2_nocomma = hall_2.split(',');
	var halls_2 = [];
	var j = 0;
	var tempnumb;
	var tempnum = [];
	var tempalpha;
	for(var i = 0; i < hall_2_nocomma.length; i++)
	{
		// Trim the excess whitespace.
		hall_2_nocomma[i] = hall_2_nocomma[i].replace(/^\s*/, "").replace(/\s*$/, "");
		
		if(hall_2_nocomma[i].indexOf('-') === -1)
		{
			tempnumb = hall_2_nocomma[i].replace(/[^0-9]+/ig,"");//number
			tempalpha = hall_2_nocomma[i].replace(/[^a-z]/gi, "");//alphabet
			halls_2[j] = tempalpha + tempnumb;
			j = j + 1;
		}
		else
		{
			temp = hall_2_nocomma[i].split('-');
			for(var l = 0; l < l.length; l++)
				temp[l] = temp[l].replace(/^\s*/, "").replace(/\s*$/, "");
			//console.log(temp);
			tempnum[0] = parseInt(temp[0].replace(/[^0-9]+/ig,""), 10);//getting numeric first value
			tempalpha = temp[0].replace(/[^a-z]/gi, "");//getting alphabetic first value
			if(typeof temp[1] != 'undefined')
			{
			tempnum[1] = parseInt(temp[1].replace(/[^0-9]+/ig,""), 10);//getting numeric second value
			if(tempnum[0] <= tempnum[1]){
			for(var k = tempnum[0]; k <= tempnum[1]; k++)
			{
				halls_2[j] = tempalpha+ "" + "" + k;
				j = j + 1;
			}
			}
			}
		}
	}
	//console.log(halls_1);
	var final2 = '<b>';
	for(var i = 0; i < halls_2.length; i++){
		halls_2[i] = halls_2[i].toUpperCase();
	}
	for(var i = 0; i < halls_2.length; i++){
		final2 += halls_2[i];
		if((i+1) != halls_2.length)
			final2 += ', ';
	}
	if (hall_2 == ''){
		document.getElementById('javascripter2').innerHTML = "<i><< appears when typed >></i>";
		document.getElementById('javatotal2').innerHTML = '1st';
		document.getElementById('javascripter2').className = 'cooling';
		cont2 = 0;
	}else{
		var k2 = halls_2.length - 1;
		if(k2 >= session2)//to change color at right time
		{
			document.getElementById('javascripter2').className = 'correct';
			//global save situation
			cont2 = 1;
		}
		else{
			document.getElementById('javascripter2').className = 'error';
			cont2 = 0;
		}
		document.getElementById('javascripter2').innerHTML = '<b>' + final2 + '</b>';
		if((k2%10 == 1))
			document.getElementById('javatotal2').innerHTML = k2 + 'st';
		else if((k2%10 == 2))
			document.getElementById('javatotal2').innerHTML = k2 + 'nd';
		else if((k2%10 == 3))
			document.getElementById('javatotal2').innerHTML = k2 + 'rd';
		else
			document.getElementById('javatotal2').innerHTML = k2 + 'th';
	}
	
	// below one is for the second session.
	//if ()
}

/*function checkhalls(){
	//if(document.forms[0].elements[0].value == '')
		//document.getElementById('javascripter1').innerHTML = "<i><< appears when typed >></i>";
	document.hallform.hall.focus();
}*/
</script>
<?php
/*datePrint dateReal pdfDate
sessionEdited sessionPrint sessionReal
sessionNoh sessionOnly sessionNoh2
*/
if($back == 0)
{
$filedata = "<?php
\$datePrint = '".$datePrint."';
\$dateReal = '".$dateReal."';
\$pdfDate = '".$pdfDate."';
\$sessionPrint = '".$sessionPrint."';
\$sessionReal = '".$sessionReal."';
\$page_number = '".$page_number."';
\$sessionNoh = ".$sessionNoh.";";
if(isset($sessionNoh2))
$filedata .= "
\$sessionNoh2 = ".$sessionNoh2.";";
if(isset($sessionEdited))
$filedata .= "
\$sessionEdited = '".$sessionEdited."';";
if(isset($sessionOnly))
$filedata .= "
\$sessionOnly = '".$sessionOnly."';";

if(isset($mainarranger))
$filedata .= "
\$mainarranger = '".$mainarranger."';";
else
$filedata .= "
\$mainarranger = 'notnone';";

if(isset($ttlbFN)){
if($ttlbFN != 0)
$filedata .= "
\$ttlbFN = ".$ttlbFN.";";
elseif(isset($ttlbAN))
$filedata .= "
\$ttlbNone = ".$ttlbAN.";";
}
if(isset($ttlbAN)){
if($ttlbAN != 0)
$filedata .= "
\$ttlbAN = ".$ttlbAN.";";
elseif(isset($ttlbFN))
$filedata .= "
\$ttlbNone = ".$ttlbFN.";";
}
if(isset($ttlbNone))
$filedata .= "
\$ttlbNone = ".$ttlbNone.";";
$filedata .= "
?>";
//echo $filedata.".</div>";//for check!!!!
$file = fopen($pickfile, "w+");
fwrite($file, $filedata);
fclose($file);

$file = fopen($authfile, "w+");
fclose($file);
}
require 'downfall.php';

?>