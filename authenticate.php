<?php
$id = 3;
//if(!isset($_SESSION['dateReal']) && !isset($_SESSION['datePrint']) && !isset($_SESSION['sessionReal']) && !isset($_SESSION['sessionPrint']))
//	header('Location: blunder.php');
$authfile = "settings//authenticate.php";
$hallfile = "settings//halls.php";
require 'prime.php';
require 'settings//pick.php';
require 'settings//authenticate.php';
//session_start();
if(!isset($sessionHall) || isset($_POST["hall"]))
	$sessionHall = $_POST["hall"];
else
	$_POST['hall'] = $sessionHall;// $_SESSION["hall"];
$input = $_POST["hall"];
if (isset($_POST["hall2"]))
{
	if(!isset($sessionHall2) || isset($_POST["hall2"]))
		$sessionHall2 = $_POST["hall2"];
	else
		$_POST['hall2'] = $sessionHall; //$_SESSION["hall2"];
	$input2 = $_POST["hall2"];
}
$mm;
//$dateReal;
//$sessionReal;

//$dateReal = $_SESSION['dateReal'];
//$datePrint = $_SESSION['datePrint'];
//print_r($_SESSION['sessionReal']);
//$sessionReal = $_SESSION['sessionReal'];
//$sessionPrint = $_SESSION['sessionPrint'];

$input = str_replace(' ', '', $input);//removes spaces
$input = preg_replace('/\s+/', '', $input);//removes whitespaces
$ipcsv = explode(",", $input);//temporary comma values also contains hypen
$inputcsv = array();//orignal comma values also contains hypen
$iphsv = array();//temporary hyphen separated values does not contain any csv
$matches = array();//
$temparray = array();
$allhalls = array();

if (isset($_POST["hall2"]))
{
	$input2 = str_replace(' ', '', $input2);//removes spaces for 2nd hall variable
	$input2 = preg_replace('/\s+/', '', $input2);//removes whitespaces for 2nd hall variable
	$ipcsv2 = explode(",", $input2);//temporary comma values also contains hypen for 2nd hall variable
	$inputcsv2 = array();//orignal comma values also contains hypen for 2nd hall variable
	$iphsv2 = array();//temporary hyphen separated values does not contain any csv for 2nd hall variable
	$matches2 = array();//
	$temparray2 = array();
	$allhalls2 = array();
	$counter2 = 0;
}

$counter = 0;
//$_SESSION['date'] = 0;
//$sessionDate = 0;		NOT USED
foreach($ipcsv as $slice)//removes any additional empty, null or slice values from $ipcom to form $inputcomma
{
	if( isset($slice) && empty($slice)==FALSE && is_null($slice)==FALSE)
		$inputcsv[] = strtoupper($slice);
}
foreach($inputcsv as $slice)//converts hypenated numbers to series of numbers
{
	$saver = 0;
	if( stripos($slice, '-') !== FALSE )//if the $slice contains hypen
	{
		$iphsv = array();
		$numbers = array();
		$letters = array();
		$temparray = array();
		$iphsv = explode("-", $slice);
		//print_r($iphsv);
		foreach($iphsv as $piece)
		{
			preg_match_all('/(\d)|(\w)/', $piece, $matches);
			$numbers[] = implode($matches[1]);
			$letters[] = implode($matches[2]);
		}
		//print_r($numbers);
		//print_r($letters);
		//$letters[0] will be the class room alphabet ex: 'D23', $letters[0] will be 'D'
		$saver = 1;
	}
	if ($saver == 0)
	{
		if ($counter < $sessionNoh) //$_SESSION['noh'])
		{
			$allhalls[] = $slice;
			$counter = $counter + 1;
		}
		else
			$counter = $counter + 1;
	}
	else
	{
		for($i = $numbers[0]; $i <= $numbers[1]; $i++)
		{
			$string = $letters[0].' '.$i;
			$string = str_replace(' ', '', $string);
			if ($counter < $sessionNoh) //$_SESSION['noh'])
			{
				$allhalls[] = $string;
				$counter = $counter + 1;
			}
			else
				$counter = $counter + 1;
		}
	}
}
if(isset($_POST["hall2"]))
{
foreach($ipcsv2 as $slice)//removes any additional empty, null or slice values from $ipcom to form $inputcomma
{
	if( isset($slice) && empty($slice)==FALSE && is_null($slice)==FALSE)
		$inputcsv2[] = strtoupper($slice);
}
foreach($inputcsv2 as $slice)//converts hypenated numbers to series of numbers
{
	$saver = 0;
	if( stripos($slice, '-') !== FALSE )//if the $slice contains hypen
	{
		$iphsv2 = array();
		$numbers2 = array();
		$letters2 = array();
		$temparray2 = array();
		$iphsv2 = explode("-", $slice);
		//print_r($iphsv);
		foreach($iphsv2 as $piece)
		{
			preg_match_all('/(\d)|(\w)/', $piece, $matches2);
			$numbers2[] = implode($matches2[1]);
			$letters2[] = implode($matches2[2]);
		}
		//print_r($numbers);
		//print_r($letters);
		//$letters[0] will be the class room alphabet ex: 'D23', $letters[0] will be 'D'
		$saver = 1;
	}
	if ($saver == 0)
	{
		if ($counter2 < $sessionNoh2) //$_SESSION['noh2'])
		{
			$allhalls2[] = $slice;
			$counter2 = $counter2 + 1;
		}
		else
			$counter2 = $counter2 + 1;
	}
	else
	{
		for($i = $numbers2[0]; $i <= $numbers2[1]; $i++)
		{
			$string2 = $letters2[0].' '.$i;
			$string2 = str_replace(' ', '', $string2);
			if ($counter2 < $sessionNoh2) //$_SESSION['noh2'])
			{
				$allhalls2[] = $string2;
				$counter2 = $counter2 + 1;
			}
			else
				$counter2 = $counter2 + 1;
		}
	}
}

}

	echo "<div class='desc'><h1 id='hdesc' class='detail'>Details</h1>";
	echo "<br> \nExam Date: <b>".$datePrint."</b>";
	if(isset($_POST["hall2"]))
		echo"<br><br> \nSession: <b>FN</b>";
	else
		echo "<br> \nSession: <b>".$sessionPrint."</b>";
	echo "<br> \nNo. of required Exam-Halls: ".$sessionNoh; //$_SESSION['noh'];
	echo "<br> \nNo. of entered Exam-Halls: ".$counter;
	echo "<br> \nExam-Halls selected:<b> ";
	foreach ($allhalls as $piece)
	{
		echo " \n".$piece." ";
	}
	echo "</b>";
	if(isset($_POST["hall2"]))
	{
		echo"<br><br> \nSession: <b>AN</b>";
		echo "<br> \nNo. of required Exam-Halls: ".$sessionNoh2;//$_SESSION['noh2'];
		echo "<br> \nNo. of entered Exam-Halls: ".$counter2;
		echo "<br> \nExam-Halls selected:<b> ";
		foreach ($allhalls2 as $piece)
		{
			echo " \n".$piece." ";
		}
		echo "</b>";
	}
	
	if(isset($_POST["hall2"]) && ( ($counter < $sessionNoh) || ($counter2 < $sessionNoh2) ) )
	{
	  if( ($counter < $sessionNoh) && ($counter2 < $sessionNoh2) )
	  {
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3><b>Cannot proceed:</b> <br>Required number of halls for both FN & AN sessions are not selected.";
		echo "<br>Type the exam-halls for both <b>FN & AN sessions</b> again by going back to previous page.<br></div>";
		//$_SESSION["bis_picker"] = 2;
		$sessionBisPick = 2;
		echo "<center>\n<form action='bis_pick.php' method='POST'>\n	<input type='submit' value='Previous Page' class='button'>\n</form><center>";
	  }
	  elseif($counter < $sessionNoh)//$_SESSION['noh'])
	  {
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3><b>Cannot proceed:</b> <br>Required number of halls for FN session are not selected.";
		echo "<br>Type the exam-halls for <b>only FN session</b> again by going back to previous page.<br></div>";
		//$_SESSION["bis_picker"] = 0;
		$sessionBisPick = 0;
		echo "<center>\n<form action='bis_pick.php' method='POST'>\n	<input type='submit' value='Previous Page' class='button'>\n</form><center>";
	  }
	  elseif($counter2 < $sessionNoh2)//$_SESSION['noh2'])
	  {
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3><b>Cannot proceed:</b> <br>Required number of halls for AN session are not selected.";
		echo "<br>Type the exam-halls for <b>only AN session</b> again by going back to previous page.<br></div>";
		//$_SESSION["bis_picker"] = 1;
		$sessionBisPick = 1;
		echo "<center>\n<form action='bis_pick.php' method='POST'>\n	<input type='submit' value='Previous Page' class='button'>\n</form><center>";
	  }
	}
	elseif($counter < $sessionNoh)//$_SESSION['noh'])
	{
		echo "<br><h3 id='hdesc' class='error'><br>Error</h3><b>Cannot proceed:</b> <br>Required number of halls are not selected.";
		echo "<br>Type the exam-halls again by going back to previous page.<br></div>";
		echo "<center>\n<form action='bis_pick.php' method='POST'>\n	<input type='submit' value='Previous Page' class='button'>\n</form><center>";
	}
	else
	{
		echo "<br><br><div class='correct'>All required details are collected succesfully.</div>";
		if (isset($_POST["hall2"]))
		{
		  if (($counter > $sessionNoh) || ($counter2 > $sessionNoh2))
			echo " The required halls are selected in entered order. Excess exam-halls are filtered off.<br>";
		}
		elseif (($counter > $sessionNoh)) //$_SESSION['noh']))
			echo " The required halls are selected in entered order. Excess exam-halls are filtered off.<br>";
		echo "Click <i class='correct'>'Arrange'</i> button to finish the task.";
		//$allhalls[] = 'D3';
		//$_SESSION['ehalls'] = $allhalls;
		$sessioneHalls = $allhalls;
		if (isset($_POST["hall2"]))
			$sessioneHalls2 = $allhalls2; //$_SESSION['ehalls2'] = $allhalls2;
		//print_r($_SESSION['ehalls2']);
		/*unset($_POST["hall2"]);
		unset($_SESSION["hall"]);
		unset($_SESSION["hall2"]);
		unset($_SESSION['only']);*/
		echo "</div><br>\n<center><form action='hustling.php' method='POST'>\n	<input type='submit' value=' A R R A N G E ' class='button'>\n</form></center>";
	}
	require 'downfall.php';

//file writing process starts here
/*
$sessionBisPick
$sessionDate//		NOT USED
$sessionHall
$sessionHall2
array
$sessioneHalls
$sessioneHalls2
*/

$file = fopen($authfile, "w+");
$filedata = "<?php
";
if (isset($sessionBisPick))
	$filedata .= "\$sessionBisPick = ".$sessionBisPick.";
";/*
if (isset($sessionDate))		NOT USED
	$filedata .= "\$sessionDate = '".$sessionDate."';
";*/
if (isset($sessionHall)){
	$filedata .= "\$sessionHall = '".$sessionHall."';
";
}
if (isset($sessionHall2)){
	$filedata .= "\$sessionHall2 = '".$sessionHall2."';
";
}
$filedata .= "?>";

fwrite($file, $filedata);
fclose($file);

$file = fopen($hallfile, "w+");


$filedata = "<?php
\$dateReal = '".$dateReal."';
\$pdfName = '".$pdfDate."';
\$sessionReal = '".$sessionReal."';
\$mainarranger = '".$mainarranger."';
\$page_number = ".$page_number.";
";
if (isset($sessioneHalls)){
	$filedata .= "\$sessioneHalls = array(";
	for($i = 0; $i < count($sessioneHalls); $i++)
	{
		$filedata .= '"'.$sessioneHalls[$i].'"';
		if (($i + 1) != count($sessioneHalls))
			$filedata .= ', ';
	}
	$filedata .= ");
";
}
if (isset($sessioneHalls2)){
	$filedata .= "\$sessioneHalls2 = array(";
	for($i = 0; $i < count($sessioneHalls2); $i++)
	{
		$filedata .= '"'.$sessioneHalls2[$i].'"';
		if (($i + 1) != count($sessioneHalls2))
			$filedata .= ', ';
	}
	$filedata .= ");
";
}
$filedata .= "?>";
fwrite($file, $filedata);
fclose($file);
?>