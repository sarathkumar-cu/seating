<?php

$slice = ' 303 10 114 106 ';
			$slice = str_replace(' ', '', $slice);// removes any extra spaces in reg nos.
			$slice = preg_replace('/\s+/', '', $slice);// removes any whitespaces in reg nos.
$a = array('30308106', '30309106', '303' . '10' . '106', '31011106', '31012106', '31013106', '31014106');

foreach ($a as $a){
if( stripos($slice, $a) !== FALSE )//if slice contains a its true
	echo $slice.' is slicer & '.$a.' is a <br><h1> Y e s .</h1><br><br>';
else
	echo $slice.' is slice & '.$a.' is a <br><h1> N o .</h1><br><BR>';
}
/**//*
$a = '';
if( strpos( $a, 'FN(' ) !== FALSE )//if slice contains a its true
	echo '<h1> Y e s .</h1><br><br>';
else
	echo '<h1> N o .</h1><br><BR>';
	/**/
?>