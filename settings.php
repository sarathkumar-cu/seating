<?php
$setfile = "settings//settings.php";

$blunder = 0;
$a1 = 0;
$a2 = 0;
if( isset($_POST['session']) ){
	$a = $_POST['session'];
	$a1 = 1;
	if($a == 'FN')
		$session = 0;
	elseif($a == 'AN')
		$session = 1;
	elseif($a == 'FNAN')
		$session = 2;
	elseif($a == 'editFN')
		$session = 3;
	elseif($a == 'editAN')
		$session = 4;
	else
		$blunder = 1;
}
if( isset($_POST['page']) ){
	$a = $_POST['page'];
	$a2 = 1;
	if($a == 'page_yes')
		$page = 0;
	elseif($a == 'page_no')
		$page = 1;
	else
		$blunder = 1;
}
//if(  )
$write = "<?php
";
if($blunder == 1)
	header('Location: blunder.php');

if($a1 == 1)
$write .= "	\$settings_session = $session;
";
if($a2 == 1)
$write .= "	\$settings_page = $page;
";
$write .= "?>";

$file = fopen($setfile, "w+");
fwrite($file, $write);
fclose($file);

header('Location: ../seating');
?>