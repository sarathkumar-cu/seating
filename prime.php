<!DOCTYPE html>
<html>
<head>
<title>&#9733;AIHT&#9733; Seating Planner</title>
<link rel="shortcut icon" href="bauble/favicon.ico">
<link rel="stylesheet" type="text/css" href="allure.css?v=3">
<?php
if (isset($tempatch) && $tempatch == 'redirect')
	echo '<meta http-equiv="refresh" content="4;url=spruce.php" /><style>*{cursor:progress;}</style>';//spruce.php
elseif (isset($tempatch) && $tempatch == 'redirect2')
	echo '<meta http-equiv="refresh" content="1;url=aquisitio.php" /><style>*{cursor:wait;}</style>';//final_main.php
?>
</head>
<body<?php if ($id == 2) echo ' onLoad = "document.hallform.hall.focus()" ' ?>>
<?php
/*
if (isset($tempatch) && $tempatch == 'redirect')
	echo '<div class="entire">';
elseif (isset($tempatch) && $tempatch == 'redirect2')
	echo '<div class="entire2">';
else
	echo '<div class="entiredefault">';*/

?>
<div id='header'<?php
//if (isset($tempatch) && $tempatch == 'redirect')
	//echo ' class="entire"';

?>>
<h1><center><a class='star'>&#9733;</a>Anand Institute of Higher Technology<a class='star'>&#9733;</a></center></h1>
<h2><center>Semester Examination - Seating Planner</center></h2>
</div>
<div id='pane'>
	<center>
	<span<?php if ($id == 1) echo " id='focus'"?>>UPLOAD</span>
	<span<?php if ($id == 2) echo " id='focus'"?>>SELECT</span>
	<span<?php if ($id == 3) echo " id='focus'"?>>CONFIRM</span>
	<span<?php if ($id == 4) echo " id='focus'"?>><?php if (isset($blunder)) echo'ERROR'; else echo'PRINT';?></span>
	</center>
</div>
<div id='content'>
	<?php if ($id != 1) echo"<div id='restartcenter'>\n<center id='restart'><a href='../seating'><p>RESTART!</p></a></center>\n</div>";
			elseif (!isset($idoptions)) echo"<div id='staffcenter'>\n<center id='staff'><a href='options.php'><p>STAFF</p></a></center>\n</div><div id='restartcenter'>\n<center id='restart'><a href='options.php'><p>OPTIONS</p></a></center>\n</div>";
			  else echo"<div id='restartcenter'>\n<center id='restart'><a href='../seating'><p>HOME!</p></a></center>\n</div>";?>
	<div id='main'>
	