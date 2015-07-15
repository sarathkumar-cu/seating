<?php
$id = 2;
require 'prime.php';
//require 'fileprocess.php';
echo "<div class='desc'><h1 id='hdesc' class='detail'>Details</h1><center>PDF or Text file was successfully uploaded before</center><br><hr>";
echo "<br>Note: <a class='error'>An extra hall</a> is asked for avoiding an error during final-arrangement process.<br>";
//if(!isset($_SESSION['sessionReal']))
	//header ('Location: blunder.php');
//session_start();
//$bis_pickfile = "settings//bis_pick.php"; NOT USED
require 'settings//pick.php';
require 'settings//authenticate.php';
//$sessionReal = $_SESSION['sessionReal'];
//$sessionBisPicker
if(isset($sessionBisPick))
{
	if(($sessionBisPick == 2))
	{/*
	mainarranger('FN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (FN): ".$ttlbFN."<br>\nTotal Number of Exam Halls required (FN): <b>".$calc_rooms."</b>\n";
	/*mainarranger('AN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh2'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh2;
	echo"<br>\nTotal Number of Students (AN): ".$ttlbAN."<br>\nTotal Number of Exam Halls required (AN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n";
	echo"<label for='hall' id='label'>Halls FN</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	echo"<label for='hall2' id='label'>Halls AN</label>\n\t<input type='text' name='hall2' size=30 id='input'><br>\n";
	}
	elseif(($sessionBisPick == 0))
	{	/*
	mainarranger('FN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (FN): ".$ttlbFN."<br>\nTotal Number of Exam Halls required (FN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n";
	echo"<input name='hall2' type='hidden' value='".$sessionHall2."'>";
	echo"\n<label for='hall' id='label'>Halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
	elseif(($sessionBisPick == 1))
	{/*
	mainarranger('AN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (AN): ".$ttlbAN."<br>\nTotal Number of Exam Halls required (AN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n";
	echo"<input name='hall' type='hidden' value='".$sessionHall."'>";
	echo"\n<label for='hall2' id='label'>Halls</label>\n\t<input type='text' name='hall2' size=30 id='input'><br>\n";
	}
	unset($sessionBisPick);
}
else
{
	if(isset($sessionEdited) && $sessionEdited != 'FN' && $sessionEdited != 'AN')
	{
	if($sessionReal == 'FNAN')
	{/*
	mainarranger('FN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (FN): ".$ttlbFN."<br>\nTotal Number of Exam Halls required (FN): <b>".$calc_rooms."</b>\n";/*
	mainarranger('AN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh2'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh2;
	echo"<br>\nTotal Number of Students (AN): ".$ttlbAN."<br>\nTotal Number of Exam Halls required (AN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n";
	echo"<label for='hall' id='label'>Halls FN</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	echo"<label for='hall2' id='label'>Halls AN</label>\n\t<input type='text' name='hall2' size=30 id='input'><br>\n";
	}
	elseif($sessionReal == 'FN')
	{	/*
	mainarranger('FN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (FN): ".$ttlbFN."<br>\nTotal Number of Exam Halls required (FN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
	elseif($sessionReal == 'AN')
	{/*
	mainarranger('AN');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (AN): ".$ttlbAN."<br>\nTotal Number of Exam Halls required (AN): <b>".$calc_rooms."</b></div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
	}
	else
	{/*
	mainarranger('none');
	$calc_rooms = intval($ttlb / 25) + 2;
	if($ttlb % 25 != 0)
		$calc_rooms = $calc_rooms + 1;
	$_SESSION['noh'] = $calc_rooms;*/
	$calc_rooms = $sessionNoh;
	echo"<br>\nTotal Number of Students (".$sessionReal."): ".$ttlbNone."<br>\nTotal Number of Exam Halls required (".$sessionReal."): <b>".$calc_rooms."</b>";
	echo"</div>\n";
	echo"<br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
	}
}

/*{
echo "<center>\n<form action='try02index.php'>\n	<input type='submit' value='Click Here to Upload Again' class='button'>\n</form></center>";
echo"<br><hr><br>\n<center><form action='authenticate.php' method='POST' name='hallform'>\n\n<label for='hall' id='label'>Halls</label>\n\t<input type='text' name='hall' size=30 id='input'><br>\n";
}*/
echo"<input type='submit' value='Check Again!' class='button'>\n</form></center>";
echo "<br><br>\n<div class='guide'><h2 id='hdesc' class='detail'>Guidelines</h2>";
	echo"These are the rules for selecting your exam halls.<br><b class='correct'>Do's:</b><br>";
	echo"<ul><li>To select range, say from D1 to D20 type <b class='correct'>D1 - D20</b>.</li>";
	echo"<li>To select individuals, type <b class='correct'>C2, C6, A1</b>.</li>";
	echo"<li>Mixed values can be entered.</li>";
	echo"<li><b>Example</b>: A1 - A5, A7, D1 - D5, C1</li>";
	echo"<li>Number of Halls required must be satisfied. If 40 exam halls are required, then atleast 40 halls must be chosen. ";
	echo"Can select more than that which will be filter off in next step.<br><br><b class='error'>Dont's:</b></li>";
	echo"<li>Only use above mentioned correct format. Any words like 'and', 'or', 'to', etc. causes error.</li>";
	echo"<li>Halls must be alpha-numberic and no spaces in between. <b class='error'>A 2, 3C, A</b> - Wrong</li>";
	echo"<li>Range must be of same alphabet, say if you want A1 to A20 and B1 to B6. </li>";
	echo"Correct way is <b class='correct'>A1 - A20, B1 - B6</b>. But, <b class='error'>A1 - B6</b> is Wrong.</div>";
require 'downfall.php';
?>