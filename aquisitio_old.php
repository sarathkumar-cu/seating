<?php
/*aquisitio <- aquirere
lat 	  ad - to
lat quaerere - seek
lat->adquirere
oldfrench->aquerre
midEng->acquere

*/
//if(!isset($_SESSION['dateReal']) || !isset($_SESSION['sessionReal']) || !isset($_SESSION['ehalls']))
//  header('Location: blunder.php');

    require'fpdf.php';
	session_start();
	require'fileprocess.php';
	//$myfile = $_SESSION['urltxt'];
	$header = array('Hall No.', 'Deg./Dept.', 'Total', 'Reg Nos.');
	$strmax = 50;
	$tempreg = 'cuSK';
	$date = $_SESSION['dateReal'];
	$session = $_SESSION['sessionReal'];
	$cursession = $session;
	$allhalls = $_SESSION['ehalls'];//used in nexthal(); function
	if($_SESSION['sessionReal'] == 'FNAN')
		$allhalls2 = $_SESSION['ehalls2'];
	// ----------- Table Column widths ----------- //
				$width = array(20, 27, 16, 120);
				$height= array( 7,  6, 0);
	//---------------------------------------------//
function nexthal($a)//returns next exam hall if current value is passed through it
{
	global $allhalls;
	$count = 0;
	for($i = 0; $i < count($allhalls); $i++)
	{
		if($allhalls[$i] == $a)
			break;
		//if($i != 0)
		$count = $count + 1;
	}
	if($count == count($allhalls))
	{
		$temp104 = $allhalls[0];
	}
	else
	{
		$temp104 = $allhalls[($i + 1)];
	//print_r($allhalls);
	}
	return $temp104;
}

class PDF extends FPDF
{
	function ImprovedTable($header, $width, $height)
	{
		// Header
		$this->SetFont('', 'IU');
		for($i=0;$i<count($header);$i++)
			$this->myCell($width[$i],$height[0],$header[$i],1,0,'C');
		$this->SetFont('');
		$this->Ln();
	}
function myCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
{
	global $header, $width, $height, $newpage, $curdpt, $curhal, $printdpt, $newpager;
	// Output a cell
	$k = $this->k;
	if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
	{
		// Automatic page break
		for($i=0; $i<count($header); $i++)
			$this->Cell($width[$i], $height[2], '', 'BLR');
		$x = $this->x;
		$ws = $this->ws;
		if($ws>0)
		{
			$this->ws = 0;
			$this->_out('0 Tw');
		}
		$this->AddPage($this->CurOrientation,$this->CurPageSize);
		$this->x = $x;
		if($ws>0)
		{
			$this->ws = $ws;
			$this->_out(sprintf('%.3F Tw',$ws*$k));
		}
		$this->Ln();
		for($i=0; $i<count($header); $i++)
			$this->Cell($width[$i], $height[2], '', 'TLR');
		$this->Ln();
		$this->ImprovedTable($header, $width, $height);
		if($newpage != 1)
		{
			$newpager = 1;
		}
		$newpage = 1;
	}
	if($w==0)
		$w = $this->w-$this->rMargin-$this->x;
	$s = '';
	if($fill || $border==1)
	{
		if($fill)
			$op = ($border==1) ? 'B' : 'f';
		else
			$op = 'S';
		$s = sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
	}
	if(is_string($border))
	{
		$x = $this->x;
		$y = $this->y;
		if(strpos($border,'L')!==false)
			$s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
		if(strpos($border,'T')!==false)
			$s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		if(strpos($border,'R')!==false)
			$s .= sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		if(strpos($border,'B')!==false)
			$s .= sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
	}
	if($txt!=='')
	{
		if($align=='R')
			$dx = $w-$this->cMargin-$this->GetStringWidth($txt);
		elseif($align=='C')
			$dx = ($w-$this->GetStringWidth($txt))/2;
		else
			$dx = $this->cMargin;
		if($this->ColorFlag)
			$s .= 'q '.$this->TextColor.' ';
		$txt2 = str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
		$s .= sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt2);
		if($this->underline)
			$s .= ' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
		if($this->ColorFlag)
			$s .= ' Q';
		if($link)
			$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
	}
	if($s)
		$this->_out($s);
	$this->lasth = $h;
	if($ln>0)
	{
		// Go to next line
		$this->y += $h;
		if($ln==1)
			$this->x = $this->lMargin;
	}
	else
		$this->x += $w;
}
function Header() //used to set header for pdf file
{
	global $date, $cursession;
	$this->SetPDFHeader($date,$cursession);

}
function Footer() //used to set footer for pdf file
{

	$this->SetPDFFooter();

}
}

	
	/*function disp_full() //displays all the reg nos. from the file
	{
	  global $b, $totalb;
	  $a = 0;
	  while($a<90)
	  {
	    if (!empty($b[$a])) 
	    {
	      e1cho "<b>".dpt($a).": ".$totalb[$a]." Students</b><br>";
	      disp_dpt($b[$a], 0, $totalb[$a]);
	      e1cho"<br><br>";
	    }
	    $a = nextdpt($a);
	  }
	}*/
	
	
	
	function disp_rqd($a, $c, $d) //displays only the required reg nos.        *******Cell() used for department and nos students
	{
	  global $b, $totalb, $pdf, $height, $width, $printdpt, $curdpt, $curhal, $newpage;
	  $k = ($d - $c);
	  if(!empty($b[$a]))
	  {
	    //ec1ho"<b>".dpt($a)." ".$k." Students</b><br>";
		$curdpt = dpt($a);
		/*if($newpage == 1)
		{
			$pdf->myCell($width[0], $height[1], $curhal, 'LR');
			$pdf->myCell($width[1], $height[1], $curdpt, 'LR');
			$pdf->SetFont('Arial', '', 7);
			$pdf->myCell($width[2], $height[1], '  '.$k, 'LR');
			$pdf->SetFont('Arial', '', 14);
		}*/
		if($printdpt == 0  && $newpage != 1)
			$pdf->myCell($width[0], $height[1], '', 'LR');
		if($printdpt == 0  && $newpage != 0 && ($d-$c) == 13)
			$pdf->myCell($width[0], $height[1], '', 'LR');
		/*if($printdpt == 0  && $newpage == 1)
			$pdf->myCell($width[0], $height[1], $curhal, 'LR');*/
			
		{
			$pdf->myCell($width[1], $height[1], $curdpt, 'LR');
			$pdf->SetFont('Arial', '', 7);
			$pdf->myCell($width[2], $height[1], '  '.$k, 'LR');
			$pdf->SetFont('Arial', '', 14);
		}
		$newpage = 0;
		disp_dpt($b[$a], $c, $d);
		//ec1ho"<br><br>";
	  }
	}
	
	function disp_dpt($reg, $a, $total) //used by disp_rqd() and disp_full() functions
	{
    global $w, $strmax, $tempreg, $printd_no, $firstloop;
	$in = null; $n = null; $pcountr=0; $firstloop = 1; //a is the starting number from which number to display.
    for($i=$a; $i < $total; $i++)  
	{ 
	  if (isset($reg[$i]))
	  {
	    $p = $reg[$i]; //present (current) used reg no.
	    intval($reg[$i]);
	 	$in = $reg[$i] + 1; // imaginary next reg no. adds one to $p
		if (isset($reg[$i+1]))
		{  
		  intval($reg[$i+1]);
		  $n = $reg[$i+1]; // this is the real next reg no. present in the array
		}
		if ($i==$a)
		{
		  //ec1ho $reg[$i];
		  ////////////////////***********must try below function at start***********////////////////////
		  $reg[$i] = str_replace(' ', '', $reg[$i]);// removes any extra spaces in between reg nos.
		  $reg[$i] = preg_replace('/\s+/', '', $reg[$i]);// removes any whitespaces in between reg nos.
		  ////// spaces and whitespaces are different in php.... so both the above funcs are mandatory
		  $tempreg = $reg[$i];
		  //$pdf->myCell($width[2], $height[1], $reg[$i], 'LR');
		  nxtchk($in, $n, 0, $p);
		  if($a+1 == $total)
		  {
			$tempreg .= '.';
			printcell1($tempreg);
		  }
		}
		elseif ($i == ($total-1))
		{
		  if($w==0 or $w==1){
		    //echo ", ".$reg[$i];
			if(strlen($tempreg.', '.$reg[$i])<$strmax){
				$reg[$i] = str_replace(' ', '', $reg[$i]);
				$reg[$i] = preg_replace('/\s+/', '', $reg[$i]);
				$tempreg .= ', '.$reg[$i]; $pcountr = 1;
				$printd_no = $reg[$i]+1;}
			else
			{
				$tempreg .= ',';
				if($firstloop == 1){
				printcell1($tempreg);
				$firstloop = 0;}
				else
				printcell($tempreg);
				$reg[$i] = str_replace(' ', '', $reg[$i]);
				$reg[$i] = preg_replace('/\s+/', '', $reg[$i]);
				$tempreg = $reg[$i];
				$pcountr = $pcountr + 1;
			}
			//$pdf->myCell($width[2], $height[1], ', '.$reg[$i], 0);
			}
		  elseif($w>1){
		    //echo " - ".$reg[$i];
			if(strlen($tempreg.' - '.$reg[$i])<$strmax){
				$reg[$i] = str_replace(' ', '', $reg[$i]);
				$reg[$i] = preg_replace('/\s+/', '', $reg[$i]);
				$tempreg .= ' - '.$reg[$i]; $pcountr = 1;}
			else
			{
				$tempreg .= ',';
				if($firstloop == 1){
				printcell1($tempreg);
				$firstloop = 0;}
				else
				printcell($tempreg);
				$reg[$i] = str_replace(' ', '', $reg[$i]);
				$reg[$i] = preg_replace('/\s+/', '', $reg[$i]);
				$tempreg = $printd_no.' - '.$reg[$i];
				$pcountr = $pcountr + 1;
			}
			//$pdf->myCell($width[2], $height[1], ' - '.$reg[$i], 0);
			}
		}
		elseif($w==0)
		{
		  //echo ", ".$reg[$i];
			if(strlen($tempreg.', '.$reg[$i])<$strmax){
				$reg[$i] = str_replace(' ', '', $reg[$i]);
				$reg[$i] = preg_replace('/\s+/', '', $reg[$i]);
				$tempreg .= ', '.$reg[$i]; $pcountr = 1;
				$printd_no = $reg[$i]+1;}
			else
			{
				$tempreg .= ',';
				if($firstloop == 1){
				printcell1($tempreg);
				$firstloop = 0;}
				else
				printcell($tempreg);
				$reg[$i] = str_replace(' ', '', $reg[$i]);
				$reg[$i] = preg_replace('/\s+/', '', $reg[$i]);
				$tempreg = $reg[$i];
				$pcountr = $pcountr + 1;
			}
		  //$pdf->myCell($width[2], $height[1], ', '.$reg[$i], 0);
		  nxtchk($in, $n, 0, $p);
		}
		elseif($w==1)
		  nxtchk($in, $n, 1, $p);
		elseif($w>1)
		  nxtchk($in, $n, 2, $p);
	  }
	}
		if($pcountr > 0)
		{
			$tempreg .= '.';
				if($firstloop == 1){
				printcell1($tempreg);
				$firstloop = 0;}
				else
				printcell($tempreg);
			$pcountr = 0;
		}
	}
	function nxtchk($u, $v, $s, $a)   //next check value for disp_dpt function
	{
	  global $w, $pdf, $width, $height, $tempreg, $strmax, $printd_no, $firstloop;
	  if($u==$v)
	    $w = $w + 1;
	  else
	  {
	    $w = 0;
	    if($s==1){
	      //echo ", ".$a;
			if(strlen($tempreg.', '.$a)<$strmax)
			{	
			$a = str_replace(' ', '', $a);
			$a = preg_replace('/\s+/', '', $a);
			$tempreg .= ', '.$a;
			$printd_no = $a+1;}
			else
			{
				$tempreg .= ',';
				if($firstloop == 1){
				printcell1($tempreg);
				$firstloop = 0;}
				else
				printcell($tempreg);
				$a = str_replace(' ', '', $a);
				$a = preg_replace('/\s+/', '', $a);
				$tempreg = $a;
			}
		  //$pdf->myCell($width[2], $height[1], ', '.$a, 0);
		  }
	    if($s==2){
		  //echo " - ".$a;
			if(strlen($tempreg.' - '.$a)<$strmax){
				$a = str_replace(' ', '', $a);
				$tempreg .= ' - '.$a;}
			else
			{
				$tempreg .= ',';
				if($firstloop == 1){
				printcell1($tempreg);
				$firstloop = 0;}
				else
				printcell($tempreg);
				$a = str_replace(' ', '', $a);
				$tempreg = $printd_no.' - '.$a;
			}
		  //$pdf->myCell($width[2], $height[1], ' - '.$a, 0);
		  }
	  }
	}
	
	function arrange() /// **************Cell() used for hall nos.
	{
	  
	  global $ttlb, $ttli, $ttlt, $totalt, $totali, $totalb, $b, $e, $nodept, $width, $header, $pdf, $width, $height, $printdpt, $curhal;
	  $a = 1; $m; $retry = 0; $e12=1; $e13 = 1;
	  while($ttli < $ttlb)
	  {
		$z = 0;
	    while($z<90)
		{
	      $totalt[$z] = $totalb[$z] - $totali[$z]; 
		  $z = nextdpt($z);
		}
			$ttli = counting('i');
			$ttlt = counting('t');
		$z = 0;
		if($e == 12 || $e == 0)
	      $e = 13;
		elseif($e == 13)
		  $e = 12;
	    if($ttlt <= 25) //if total number of students are less than 25
	    {
		//ec1ho "<b><br>1Exam Room No. ".$a."</b><br>";
		for($i=0;$i<count($header);$i++)
			$pdf->myCell($width[$i], $height[2], '', 'TLR');
		$pdf->Ln();
		$pdf->SetFont('', 'B');
		//$curhal = 'D1';
		$curhal = nexthal($curhal);
		$pdf->myCell($width[0], $height[1], $curhal, 'LR');
		$printdpt = 1;
		$pdf->SetFont('');
		$a = $a + 1;
	      for($z = 0; $z < 90; $z = nextdpt($z))
		    if($totalt[$z] != 0){
		      $temp22 = $totalt[$z];  
			  ezar($z, $temp22);
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
			$pdf->Ln();
		$printdpt = 0;}
		for($i=0; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
		$pdf->Ln();
		}
	    elseif($e == 13) //when e == 13
		{
		  $n = 0;
		  for($z = 0; $z < 90; $z = nextdpt($z)) // normal or default arrangement procedure
		  {
		    if($totalt[$z] >= 13 && $totalt[$z]!=14)
		    {
		//ec1ho "<b><br>2Exam Room No. ".$a."</b><br>";
		for($i=0;$i<count($header);$i++)
			$pdf->myCell($width[$i], $height[2], '', 'TLR');
		$pdf->Ln();
		$pdf->SetFont('', 'B');
		//$curhal = 'D2';
		$curhal = nexthal($curhal);
		$pdf->myCell($width[0], $height[1], $curhal, 'LR');
		$pdf->SetFont('');
		$printdpt = 1;
		$a = $a + 1;
			  ezar($z, $e);
		$printdpt = 0;
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
		$pdf->Ln();
			  sub_ar($z, $e);
		for($i=0; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
		$pdf->Ln();
			  $n = 0; $e13 = 1;
			  break;
		    }
		    $n = 1; $e13 = $e13 + 1;
		  }
		}
		elseif($e == 12) //when e == 12
		{
		  $n = 0;
		  for($z = 0; $z < 90; $z = nextdpt($z)) // normal or default arrangement procedure
		  {
		    if($totalt[$z] >= 12 && $totalt[$z]!=13)
		    {
		//ec1ho "<b><br>3Exam Room No. ".$a."</b><br>";
		for($i=0;$i<count($header);$i++)
			$pdf->myCell($width[$i], $height[2], '', 'TLR');
		$pdf->Ln();
		$pdf->SetFont('', 'B');
		//$curhal = 'D3';
		$curhal = nexthal($curhal);
		$pdf->myCell($width[0], $height[1], $curhal, 'LR');
		$pdf->SetFont('');
		$printdpt = 1;
		$a = $a + 1;
			  ezar($z, $e);
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
		$pdf->Ln();
		$printdpt = 0;
			  sub_ar($z, $e);
		for($i=0; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
		$pdf->Ln();
			  $n = 0; $e12 = 1;
			  break;
		    }
		    $n = 1; $e12 = $e12 + 1;
		  }
		}
		if(isset($n) && $e12 > 1 && $e13 > 1 && $ttlt != 0)
		{
		if($n == 1)
		{
		//ec1ho "<b><br>4Exam Room No. ".$a."</b><br>";
		for($i=0;$i<count($header);$i++)
			$pdf->myCell($width[$i], $height[2], '', 'TLR');
		$pdf->Ln();
		$pdf->SetFont('', 'B');
		//$curhal = 'D4';
		$curhal = nexthal($curhal);
		for($i=0; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[1], '', 'BLR');
		$pdf->Ln();
		$pdf->myCell($width[0], $height[1], 'S4', 'LR');
		$pdf->SetFont('');
		$a = $a + 1;
		  $temp84 = 0; $z = 0;
			  $printdpt = 1;
		  while($temp84 < 26 && $z < 90)
		  {
		    if(($temp84 + $totalt[$z]) < 26 && $totalt[$z] != 0)
			{
			  $temp84 = $temp84 + $totalt[$z];
			  ezar($z, $totalt[$z]);
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
			$pdf->Ln();
			//$pdf->AddPage();
			  $printdpt = 0;
			}
			$z = nextdpt($z);
		  }
		for($i=0; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
		$pdf->Ln();
		}
		}
		/*if($n == 1) // if there exists no 12 or 13 in all depts
		{
		  $k = 0;
		  $trial = 0;// 0 adds largest value // 1 adds smallest value // 2 subdivision
		  $sub = 0;
		  if($e == 13)
		  {
		  while($k <= 12)
		  {
		    if($trial == 0)
			{
		      $z = large($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 13 && $m != 0 && ($k + $m) != 12)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 1;
			  $temp88 = 1;
			}
			elseif($trial == 1)
			{
			  $z = least($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 13 && $m != 0 && ($k + $m) != 12)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 2;
			  $temp88 = 0;
			}
			elseif($trial == 2)
			{
			  $z = least2($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 13 && $m != 0 && ($k + $m) != 12)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 2;
			  $temp88 = 0;
			}
			elseif($trial == 3)
			{
			  $z = least_2($totalt);
			  $m = $totalt[$z];
			  e1cho "z".$z."m".$m."<br>";
			  print_r($totalt);
			  $sub = 0;
			  while($k <= 13 && $m != 0)
			  {
			    if(($k + $m) < 14 && ($k + $m) != 12)
				{
				  $sub = $sub + $m;
				  $k = $k + $m;
				}
				$m = subdivide($m);
			  }
			  ezar($z, $sub);
			}
		  }
		  sub_ar(0 , $e);
		  }
		  
		  elseif($e == 12)
		  {
		  while($k <= 11)
		  {
		    if($trial == 0)
			{
		      $z = large13($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 12 && $m != 0 && ($k + $m) != 11)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 1;
			  $temp88 = 1;
			}
			elseif($trial == 1)
			{
			  $z = least13($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 12 && $m != 0 && ($k + $m) != 11)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 2;
			}
			elseif($trial == 2)
			{
			  $z = least132($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 12 && $m != 0 && ($k + $m) != 11)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 3;
			}
			elseif($trial == 3)
			{/*
			 if($k == 11)
			 {
			    $z = large13($totalt);
				$m = $totalt[$z];
			 }/
			  $z = least13_2($totalt);
			  e1cho "z=".$z."m=".$m."<br>";
			  print_r($totalt); e1cho "<br>";
			  $m = $totalt[$z];
			  $sub = 0;
			  while($m != 0)
			  {
			    if(($k + $m) <= 12 && ($k + $m) != 11)
				{
				  $sub = $sub + $m;
				  $k = $k + $m;
				}
				$m = subdivide($m);
			  }
			  ezar($z, $sub);
			}
		  }
		  sub_ar(0 , $e);
		  }
		}*/
	  }
	}  
	function sub_ar($c, $a)
	{
	  global $f, $totali, $totalt, $totalb, $ttlb, $ttlt, $ttli, $nodept, $pdf, $width, $height, $header;
	  $n = 0; $m = 0; $proceed = 0;
	  if($a == 13)
	    $f = 12;
	  elseif($a == 12)
	    $f = 13;
	  else
	    $f = 0;
	  if($ttlt < 13 && $f == 12) //if total number of students are less than 13 when $f = 12
	  {
	    for($z = 0; $z < 90; $z = nextdpt($z))
		  if($totalt[$z] != 0)
		  {
		    $temp32 = $totalt[$z];
			ezar($z, $temp32);
			$proceed = 1;
		  }
	  }
	  elseif($ttlt <= 13 && $f == 13) //if total number of students are less than 13 when $f = 13
	  {
	    for($z = 0; $z < 90; $z = nextdpt($z))
		  if($totalt[$z] != 0)
		  {
		    $temp32 = $totalt[$z];
			ezar($z, $temp32);
			$proceed = 1;
		  }
	  }
	  elseif($f == 13) //for f==13
	  {
		$n = 0;
	    for($z = 0; $z < 90; $z = nextdpt($z)) //normal or default arrangement procedure
		{
	      if($totalt[$z] >= 13 && $totalt[$z] != 14 && $z != $c)
		  {
		    ezar($z, $f);
			$n = 0;
			$proceed = 1;
			break;
		  }
		  $n = 1;
		}
	  }
	  elseif($f == 12) //for f==12
	  {
		$n = 0;
	    for($z = 0; $z < 90; $z = nextdpt($z)) //normal or default arrangement procedure
		{
	      if($totalt[$z] >= 12 && $totalt[$z] != 13 && $z != $c)
		  {
		    ezar($z, $f);
			$n = 0;
			$proceed = 1;
			break;
		  }
		  $n = 1;
		}
	  }
	  if ($n == 1)
	  {
	    $temp78=0;
	    if($f == 13)
		{
		  $z = 0;
		  while($temp78<14 && $z<90)
		  {
		    if(($temp78+$totalt[$z]) < 14 && $totalt[$z] != 0)
			{
			  $temp78 = $temp78 + $totalt[$z];
			  ezar($z, $totalt[$z]);
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
			$pdf->Ln();
			  $proceed = 1;
			}
			$z = nextdpt($z);
		  }
		  if($temp78<14 && $temp78 != $f)
		  {
			$tetotal = $f - $temp78;
			//if($tetotal == 1)
			//{
			if($totalt[$c] != 0)
			{
				ezar($c,($tetotal));
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
			$pdf->Ln();
			}
			$proceed = 1;
			$temp78 = $f;/*
			}
			else
			{
				$templarge = $totalt[0];
				for($z = 0; $z<90; $z = nextdpt($z))
				{
				  if( ($templarge < $totalt[$z]) && ($z != $c) )
				  {
					$templarge = $totalt[$z];
				  }
				}
			}*/
		  }
		}
		elseif($f == 12)
		{
		  $z = 0;
		  while($temp78<13 && $z<90)
		  {
		    if(($temp78+$totalt[$z]) < 13 && $totalt[$z] != 0)
			{
			  $temp78 = $temp78 + $totalt[$z];
			  ezar($z, $totalt[$z]);
			  $proceed = 1;
			$pdf->myCell($width[0], $height[2], '', 'LR');
		for($i=1; $i<count($header); $i++)
			$pdf->myCell($width[$i], $height[2], '', 'BLR');
			$pdf->Ln();
			}
			$z = nextdpt($z);
		  }
		  if($temp78<13 && $temp78 != $f)
		  {
			ezar($c,($f-$temp78));
			$proceed = 1;
			$temp78 = $f;
		  }
		}
	  }
	  if($proceed == 0)
	  {
		ezar($c, $f);
	  }
	/*  if($n == 1) //if there exists no 12 or 13 in a dept
		{
		  $k = 0;
		  $trial = 0;// 0 adds largest value // 1 adds smallest value // 2 subdivision
		  $sub = 0;
		  if($f == 13)
		  {
		  while($k <= 12)
		  {
		    if($trial == 0)
			{
		      $z = large13($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 13 && $m != 0)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 1;
			  $temp88 = 1;
			}
			elseif($trial == 1)
			{
			  $z = least13($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 13 && $m != 0)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 2;
			  $temp88 = 0;
			}
			elseif($trial == 2)
			{
			  $z = least13($totalt);
			  $m = $totalt[$z];
			  $sub = 0;
			  while($k <= 13 && $m != 0)
			  {
			    if(($k + $m) < 14)
				{
				  $sub = $sub + $m;
				  $k = $k + $m;
				}
				$m = subdivide($m);
			  }
			  ezar($z, $sub);
			}
		  }
		  }
		  
		  elseif($f == 12)
		  {
		  while($k <= 11)
		  {
		    if($trial == 0)
			{
		      $z = large($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 12 && $m != 0)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 1;
			  $temp88 = 1;
			}
			elseif($trial == 1)
			{
			  $z = least($totalt);
			  $m = $totalt[$z];
			  if(($k + $m) <= 12 && $m != 0)
			  {
			    $k = $k + $m;
			    ezar($z, $m);
			  }
			  else
			    $trial = 2;
			  $temp88 = 0;
			}
			elseif($trial == 2)
			{
			  $z = least($totalt);
			  $m = $totalt[$z];
			  $sub = 0;
			  while($m != 0)
			  {
			    if(($k + $m) <= 12)
				{
				  $sub = $sub + $m;
				  $k = $k + $m;
				}
				$m = subdivide($m);
			  }
			  ezar($z, $sub);
			}
		  }
		  }
		}*/
	}
	function large($reg) //returns the location of the largest number in an array when its not 14
	{
	  $i = 0;
	  $location = 0;
	  $a = 0;
	  while($i < 90)
	  {
	    if($reg[$i] != 14 && $reg[$i] != 0)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==14)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a < $reg[$n] && $reg[$n]!=14)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function least($reg) //returns the location of the smallest number in an array when its not 14
	{
	  $i = 0;
	  $location = 0;
	  $a = 200;
	  while($i < 90)
	  {
	    if($reg[$i]!=14 && $reg[$i]!=0)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==14 && $reg[0]!=0)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a > $reg[$n] && $reg[$n]!=14 && $reg[$n]!=0)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function least2($reg) //same as above but smallest will not be 1
	{
	  $i = 0;
	  $location = 0;
	  $a = 200;
	  while($i < 90)
	  {
	    if($reg[$i]!=14 && $reg[$i]!=0 && $reg[$i]!=1)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==14 && $reg[0]!=0)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a > $reg[$n] && $reg[$n]!=14 && $reg[$n]!=0 && $reg[$n]!=1)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function least_2($reg) //same as above but returns location of which is not equal to 2
	{
	  $i = 0;
	  $location = 0;
	  $a = 200;
	  while($i < 90)
	  {
	    if($reg[$i]!=14 && $reg[$i]!=0 && $reg[$i]!=2 && $reg[$i]!=1)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==14 && $reg[0]!=0 && $reg[$i]!=2)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a > $reg[$n] && $reg[$n]!=14 && $reg[$n]!=0 && $reg[$n]!=2 && $reg[$n]!=1)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function large13($reg) //returns the location of the largest number in an array when its not 13
	{
	  $i = 0;
	  $location = 0;
	  $a = 0;
	  while($i < 90)
	  {
	    if($reg[$i] != 13 && $reg[$i] != 0)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==13)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a < $reg[$n] && $reg[$n]!=13)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function least13($reg) //returns the location of the smallest number in an array when its not 13
	{
	  $i = 0;
	  $location = 0;
	  $a = 0;
	  while($i < 90)
	  {
	    if($reg[$i] != 13 && $reg[$i]!=0)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==13 && $reg[0]!=0)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a > $reg[$n] && $reg[$n]!=13 && $reg[$n]!=0)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function least132($reg) //same as above but smallest will not be 1
	{
	  $i = 0;
	  $location = 0;
	  $a = 0;
	  while($i < 90)
	  {
	    if($reg[$i] != 13 && $reg[$i]!=0 && $reg[$i]!=1)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==13 && $reg[0]!=0)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a > $reg[$n] && $reg[$n]!=13 && $reg[$n]!=0 && $reg[$n]!=1)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	function least13_2($reg) //same as above but returns location of which is not equal to 2
	{
	  $i = 0;
	  $location = 0;
	  $a = 0;
	  while($i < 90)
	  {
	    if($reg[$i] != 13 && $reg[$i]!=0 && $reg[$i]!=2 && $reg[$i]!=1)
		{
	      $a = $reg[$i];
		  $location = $i;
		  break;
		}
		$i = nextdpt($i);
	  }
	  if($a == 0 && $reg[0]==13 && $reg[0]!=0)
	    return 90;
	  $i = -1;
	  while($i < 90)
	  {
	    $n = nextdpt($i);
		if($n != 90)
	    if($a > $reg[$n] && $reg[$n]!=13 && $reg[$n]!=0 && $reg[$n]!=2 && $reg[$n]!=1)
		{
		  $a = $reg[$n];
		  $location = $n;
		}
		$i = $n;
	  }
	  return $location;
	}
	/*function combi($a)
	{
	  $k = array();
	  global $totalt;
	    for($i = 0; $i < 90; $i = nextdpt($i))
	    for($j = 0; $j < 90; $j = nextdpt($i))
	    for($m = 0; $m < 90; $m = nextdpt($i))
	    for($l = 0; $l < 90; $l = nextdpt($i))
		{
		  $p = $totalt[$i]; $q = $totalt[$j]; $r = $totalt[$m]; $s = $totalt[$l];
		  if($p != 0 && $q != 0)
		  {
		    if($p+$q == $a)
			  $k[] = $i; $k[] = $j; break;
			elseif($r != 0 && $p+$q+$r == $a)
			  $k[] = $i; $k[] = $j; $k[] = $m; break;
			elseif($s != 0 && $p+$q+$r+$s == $a)
			  $k[] = $i; $k[] = $j; $k[] = $m; $k[] = $l; break;
		  }
		}
	  return $k;
	}*/
	function subdivide($a)
	{
	  if($a == 2)
	    return 0;
	  $c = $a / 2;
	  return intval($c);
	}
	function counting($a)
	{
	  global $totali, $totalt;
	  $c = 0; $d = 0;
	  if ($a == 'i')
	    while($d<90)
		{
		  $c = $c + $totali[$d];
		  $d = nextdpt($d);
		}
	  if ($a == 't')
	    while($d<90)
		{
		  $c = $c + $totalt[$d];
		  $d = nextdpt($d);
		}
	  return $c;
	}
	function ezar($i, $j)//makes my arrange coding easier... $i denotes department number, $j denotes number of students
	{
	  global $totali, $totalt, $totalb, $ttli, $ttlt;
	  //if( isset($totali[$i+$j]) )
	  disp_rqd($i, $totali[$i], $totali[$i]+$j);
	  $totali[$i] = $totali[$i] + $j;
	  $totalt[$i] = $totalb[$i] - $totali[$i];
	  if($totalt[$i] < 0)
	     $totalt[$i] = 0;
	  $ttli = counting('i');
	  $ttlt = counting('t');
	}
	
//echo - unchanged,  e1cho - no need to change, ec1ho - changed

$strlencell = 0;
$strlenmax  = 39;
$tempreg;
function cellcheck($type, $number) // checks the length of the regno to print
{
	global $strmax;
	if($type == 'first')
	{
		$tempreg = $number;
	}
	elseif ($type == 'comma')
	{
		if(strlen($tempreg.$number)<$strmax)
			$tempreg = ', '.$number;
		else
			printcell($tempreg); $tempreg = $number;
	}
	//elseif()
}
function printcell($tempreg)
{
	global $pdf, $height, $width;
	$pdf->myCell($width[0], $height[1], '', 'LR');
	$pdf->myCell($width[1], $height[1], '', 'LR');
	$pdf->myCell($width[2], $height[1], '', 'LR');
	$pdf->myCell($width[3], $height[1], $tempreg, 'LR');
	$pdf->Ln();
}
function printcell1($tempreg)
{
	global $pdf, $height, $width;
	$pdf->myCell($width[3], $height[1], $tempreg, 'LR');
	$pdf->Ln();
}

if($session == 'FNAN')
{
	$cursession = 'FN';
}

$pdf = new PDF();
//$pdf->SetAutoPageBreak('off');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->ImprovedTable($header, $width, $height);
for($i=0;$i<count($header);$i++)
	$pdf->myCell($width[$i], $height[2], '', 'TLR');
$pdf->Ln();
if(isset($SESSION['edited']) && $SESSION['edited'] != 'FN' && $SESSION['edited'] != 'AN')
{
if($session == 'FNAN')
{
	$cursession = 'AN';
	mainarranger('FN');
	$ttli = counting('i');
	$ttlt = counting('t');
	arrange();
	//$session = $session2;
	//$cursession = $cursession2;
	$allhalls = $allhalls2;
	mainarranger('AN');
	$ttli = counting('i');
	$ttlt = counting('t');
  if($_SESSION['noh2'] != 0)
  {
	$pdf->AddPage();
	$pdf->ImprovedTable($header, $width, $height);
	arrange();
  }
}
else
{
	mainarranger($session);/*
	echo "\nttlb = ".$ttlb."\n";
	print_r($totali);
	echo "\n\n";
	print_r($totalt);
	echo "\n\n";
	print_r($totalb);*/
	$ttli = counting('i');
	$ttlt = counting('t');
	arrange();
}
}
else
{
	mainarranger('none');/*
	echo "\nttlb = ".$ttlb."\n";
	print_r($totali);
	echo "\n\n";
	print_r($totalt);
	echo "\n\n";
	print_r($totalb);*/
	$ttli = counting('i');
	$ttlt = counting('t');
	arrange();
}
//$pdf->SetFont
//$pdf->Cell(100, 7, html_entity_decode('&mdash;', ENT_NOQUOTES, 'UTF-8'));
	fclose($file);
	
	//file_put_contents ($usefile, "");
	//file_put_contents ($myfile, "");
	
	$savename = $_SESSION['pdfDate'];
$pdf->Output($savename, 'I');
?>