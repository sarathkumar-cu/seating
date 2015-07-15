<?php

$myfile = "upload\\file.txt";

/*----------BRANCH CODES--------------//
be&tech
0	104 cse	
1	205 it
2	105 eee
3	106 ece
4	107 ei
5	114 mech
6	103 civil from 12'

other programmes
30	621 mca
31	631 mba
32	251 arch from 12' with col code 3129

M.E(PG)
60	405 cse
61	415 ped - power electronics and drives
62	419 vlsi design
63	420 cim - computer integrated manufacturing
64	403 communication systems
65  431 embedded systems
 
no of depts = 16
//------------------------------------*/

//------------------------------------//
//Change the below for new departments//
//------------------------------------//
$nodept = 16;// used for counting multi dimension arrays...
$nodept2 = $nodept;

$file = fopen($myfile, 'r');
$bcode = array(
	/*B.E & B.Tech courses*/
	'0' => array('0'=>'30308104',  '1'=>'30309104',  '2'=>'30310104'), //cse
	'1' => array('0'=>'30308205',  '1'=>'30309205',  '2'=>'30310205'), //it
	'2' => array('0'=>'30308106',  '1'=>'30309106',  '2'=>'30310106'), //ec
	'3' => array('0'=>'30308105',  '1'=>'30309105',  '2'=>'30310105'), //ee
	'4' => array('0'=>'30308107',  '1'=>'30309107',  '2'=>'30310107'), //ei
	'5' => array('0'=>'30308103',  '1'=>'30309103',  '2'=>'30310103'), //civil
	'6' => array('0'=>'30308114',  '1'=>'30309114',  '2'=>'30310114'), //mech
	/*Other programmes*/
	'30' =>array('0'=>'30308621',  '1'=>'30309621',  '2'=>'30310621'), //mca
	'31' =>array('0'=>'30308631',  '1'=>'30309631',  '2'=>'30310631'), //mba
	'32' =>array('0'=>'312908251', '1'=>'312909251', '2'=>'312910251'),//arch
	/*M.E(PG) courses*/
	'60' =>array('0'=>'30308405',  '1'=>'30309405',  '2'=>'30310405'), //cse
	'61' =>array('0'=>'30308415',  '1'=>'30309415',  '2'=>'30310415'), //ped
	'62' =>array('0'=>'30308419',  '1'=>'30309419',  '2'=>'30310419'), //vlsi
	'63' =>array('0'=>'30308420',  '1'=>'30309420',  '2'=>'30310420'), //cim
	'64' =>array('0'=>'30308403',  '1'=>'30309403',  '2'=>'30310403'),	// cs
	'65' =>array('0'=>'30308431',  '1'=>'30309431',  '2'=>'30310431')	// es
); 

$y = 11; //starting year for dept code generation...
$year = date("y"); // used for generating dept codes like the one above
$z = 3; //used for noting array in dept code generation for inside... 2nd dimention
$x = '3101';// college code
$w = 0; //used by disp_dpt and nextchk functions
$b = array( array() ); //the main array which contains all reg nos.
$totalb = array(); // saves the total of indiviual department
$totali = array();// used in arrange() function
$totalt = array();// used in arrange() function
$e = 0; // used in arrange() function
$f = 0; // used in sub_ar () function
$printdpt = 0;
$printd_no = 0;// used in disp_dpt() function
$firstloop = 1;// used in disp_dpt() function
$curdpt = 'string';//current  department to be printed
$curhal = 'string';//current hall number to be printed
$newpage = 0;//used in myCell() in class PDF and 
$dept_hal = array( array() );//to store halls dept wise (numerically)
///////GLOBAL VARIABLES DECLARATION ENDS HERE///////
	
	//$a, $c, $u, $v, $s, $p, $in, $n. $i, $total
//////LOCAL VARIABLES INSIDE LOOPS MENTIONED ABOVE/////
while($y <= $year) //generates dept codes for all departments
{
    $a = $x.''.$y.'104';
	$bcode [0] [$z] = $a;
	$a = $x.''.$y.'205';
	$bcode [1] [$z] = $a;
	$a = $x.''.$y.'106';
	$bcode [2] [$z] = $a;
	$a = $x.''.$y.'105';
	$bcode [3] [$z] = $a;
	$a = $x.''.$y.'107';
	$bcode [4] [$z] = $a;
	$a = $x.''.$y.'103';
	$bcode [5] [$z] = $a;
	$a = $x.''.$y.'114';
	$bcode [6] [$z] = $a;
	
	$a = $x.''.$y.'621';
	$bcode [30] [$z] = $a;
	$a = $x.''.$y.'631';
	$bcode [31] [$z] = $a;
	$a = '3129'.$y.'251';
	$bcode [32] [$z] = $a;

	$a = $x.''.$y.'405';
	$bcode [60] [$z] = $a;
	$a = $x.''.$y.'415';
	$bcode [61] [$z] = $a;
	$a = $x.''.$y.'419';
	$bcode [62] [$z] = $a;
	$a = $x.''.$y.'420';
	$bcode [63] [$z] = $a;
	$a = $x.''.$y.'403';
	$bcode [64] [$z] = $a;
	$a = $x.''.$y.'431';
	$bcode [65] [$z] = $a;
	$y = $y + 1;
	$z = $z + 1;
}


//print_r($bcode);//for test

function nextdpt($a) //for generating the first array value to access informations
{
	/*$c = null;
	echo $a.'
';//for test*/
	if($a == 6)
	  $c = 30;
	elseif($a == 32)
	  $c = 60;
	elseif($a == 65) //****Change '64' to '65' for taking in ME.CS and ME.ES******
	  $c = 90;
	else
	  $c = $a + 1;
	return $c;
}

//echo nextdpt(63);//for test

function dpt($a) // used to display dept name in disp_rqd() and disp_full() functions
{
  $c;
  if($a==0)
   $c = 'B.E CSE';
  elseif($a==1)
   $c = 'B.Tech IT';
  elseif($a==2)
   $c = 'B.E ECE';
  elseif($a==3)
   $c = 'B.E EEE';
  elseif($a==4)
   $c = 'B.E E&I';
  elseif($a==5)
   $c = 'B.E Civil';
  elseif($a==6)
   $c = 'B.E Mech';
  elseif($a==30)
   $c = 'M.C.A';
  elseif($a==31)
   $c = 'M.B.A';
  elseif($a==32)
   $c = 'B.Arch';
  elseif($a==60)
   $c = 'M.E CSE';
  elseif($a==61)
   $c = 'M.E PED';
  elseif($a==62)
   $c = 'M.E VLSI';
  elseif($a==63)
   $c = 'M.E CIM';
  elseif($a==64)
   $c = 'M.E CmSys';
  elseif($a==65)
   $c = 'M.E EmSys';
  return $c;
}
//echo dpt(64);//for test

//----------------------------------------------------------//
//MUST ADD NEW DEPT. DETAILS ON ALL ABOVE LOOPS OR FUNCTIONS//
//----------------------------------------------------------//

//$z = 0;
function mainarranger($sessget)
{
	global $file, $line, $bcode, $ttlb, $nodept2, $nodept;
	global $b;
	global $totali, $totalt, $totalb;
	$proceeder = 0;
	unset($b); 	unset($totali); 	unset($totalb); 	unset($totalt);
	global $b;
	global $totali, $totalt, $totalb;
	$b = array( array() ); //the main array which contains all reg nos.
	$totalb = array(); // saves the total of indiviual department
	$totali = array();// used in arrange() function
	$totalt = array();// used in arrange() function
	$ttlb = 0;
	$nodept = $nodept2;
	if($sessget == 'FN')
	{
		$sessput = 'FN(';//for forenoon 'FN(' is set because PDF mismatches other words when 'FN' standing alone
		$sessputalt = 'AN(';//the alternative option
	}
	elseif($sessget == 'AN')
	{
		$sessput = 'AN(';//for afternoon
		$sessputalt = 'FN(';
	}
	if(is_resource($file))
		rewind($file);
	else
		die('Cannot open the file.<br>');
	while(!feof($file))  //stores all the register numbers from the txt file in separate arrays
	{
	  $line = fgets($file); //scans the file line by line and stores it in $line
	  $pieces = explode(" ", $line);
	  if(isset($pieces))
		for($z = 0; $z < 90; $z = nextdpt($z))
		{
		  foreach($pieces as $slice)
		  {
			$slice = str_replace(' ', '', $slice);// removes any extra spaces in reg nos.
			$slice = preg_replace('/\s+/', '', $slice);// removes any whitespaces in reg nos.
			////// spaces and whitespaces are different in php.... so both the above funcs are mandatory
		   if($sessget != 'none')
		   {
			if( stripos($slice, $sessput) !== FALSE) // if slice contains 'FN(' in it 			[   or 'AN('   ]
				$proceeder = 1;
			elseif( stripos($slice, $sessputalt) !== FALSE) // if slice contains 'AN(' in it 	[   or 'FN('   ]
				$proceeder = 0;
			if($proceeder == 1)
				foreach($bcode[$z] as $a)
					if( stripos($slice, $a) !== FALSE ) // if $slice contains $a then the statement executes : ))))
					{
						$b[$z][] = $slice; //confirms students and saves in this array
						break;
					}
		   }
		   elseif($sessget == 'none')
		    foreach($bcode[$z] as $a)
			  if( stripos($slice, $a) !== FALSE ) // if $slice contains $a then the statement executes
			  {
				$b[$z][] = $slice; //confirms students and saves in this array
				break;
			  }
		  }
		}
	}
	for( $z = 0; $z < 90; $z = nextdpt($z) )
	{
	  if(empty($b[$z]))//here, if there is no numbers in a dept, it is unset and totalb is set to 0
	  {
		unset($b[$z]);
		$totalb[$z] = 0;
		$nodept = $nodept - 1;
	  }
	  else//sorts and counts the total no of number in dept
	  {
		sort($b[$z]);
		$totalb[$z] = count($b[$z]);
	  }
	  $totali[$z] = 0;
	  $totalt[$z] = 0;
	}
	$ttlb = ((count ($b, COUNT_RECURSIVE)) - $nodept);

}

/*
mainarranger('AN');//for test
echo '
';
print_r($b);
echo '$ttlb = '.$ttlb;
echo '$nodept = '.$nodept;
echo 'count($b) = '.(count ($b, COUNT_RECURSIVE));//for test*/
?>