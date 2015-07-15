<?php
/*aquisitio <- aquirere
lat 	  ad - to
lat quaerere - seek
lat->adquirere
oldfrench->aquerre
midEng->acquere
*/

require 'fpdf.php';
//session_start();
require 'fileprocess.php';
require 'settings//halls.php';

/*
//test sessions STARTS here
//mainarranger('none');
//$_SESSION['dateReal'] = '12.11.2013';
$dateReal = '29.12.2013';
//$_SESSION['sessionReal'] = 'FN';
$sessionReal = 'FN';
$pdfName = 'Seatings 2013.12.29.pdf';
$mainarranger = 'none';
mainarranger('none');
for($i = 1; $i-1 <= ($ttlb/25); $i++)
$allhalls[] = 'D'.$i;
//$_SESSION['ehalls'] = $allhalls;
$sessioneHalls = $allhalls;
$pageoption = 'yes';
//test sessions ENDS here
*/

$header = array('Hall No.', 'Deg./Dept.', 'Total', 'Reg Nos.');
$strmax = 50;
$tempreg = 'cuSK';
$error = 0;
$errortype;
$print_hal = 0;
$print_dpt = 0;
$curdpt_no;
$hallwise = array( array ( array() ) );//hall//dept//regnos

if( isset($dateReal) && isset($sessionReal) )
{
  $date = $dateReal;
  $session = $sessionReal;
  $cursession = $session;
  $allhalls = $sessioneHalls;//used in nexthal(); function
  if($sessionReal == 'FNAN')
 	$allhalls2 = $sessioneHalls2;
}
else
{
	$error = 1;
	$blunder = 'session,date';
	goto end;
}

if($page_number == 1)
	$pageoption = 'yes';
else
	$pageoption = 'no';

	// ----------- Table Column widths ----------- //
				$width = array(20, 28, 12, 120);
				$height= array( 7,  6, 0);
	//---------------------------------------------//

function nexthal($a)//returns next exam hall if current value is passed through it
{
	global $allhalls;
	$count = 0;
	for($i = 0; $i < count($allhalls); $i++)
	{
		if($allhalls[$i] === $a)
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
	//return 'D2';
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
	global $header, $width, $height, $newpage, $curdpt, $curhal, $printdpt, $newpager, $print_hal, $print_dpt, $curtotal, $curdpt_no;
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
		if($print_hal == 0) {									//////////////////////////////////////////
			$this->SetFont('', 'B');
			$this->Cell($width[0], $height[1], $curhal, 'TLR');
			$this->SetFont('');
			if($print_dpt == 0){	//prints dept name and total
				$this->Cell($width[1], $height[1], $curdpt, 'TLR');
				$this->SetFont('Helvetica', '', 7);
				$this->Cell($width[2], $height[1], '        '.$curtotal, 'TLR');
				$this->Cell($width[2], $height[2], '  ', 'TLR');
				
				/*//department numbering
				$this->Ln();
				$this->SetFont('Helvetica', 'I', 9);
				$this->Cell($width[0], $height[1], '', 'TLR');
				$this->Cell($width[1], $height[1], 'set.'.$curdpt_no, 'TLR', 0, 'R');
				$this->Cell($width[1], $height[2], '', 'TLR');
				*/
				$this->SetFont('Helvetica', '', 14);
			}
			$this->Ln();
		}
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
		global $pageoption;
		$this->SetPDFFooter($pageoption);
	}
}
$dptinc_glbl;
function disp_rqd($a, $c, $d) //displays only the required reg nos.        *******Cell() used for department and nos students
{
  global $b, $dpt_hal, $curdpt_no, $hall_no;// $totalb, $pdf, $height, $width, $printdpt, $curdpt, $curhal, $newpage, $print_hal;
  $k = ($d - $c);
  if(!empty($b[$a]))
  {
	$curdpt = dpt($a);/*
	if($printdpt == 0  && $newpage != 1)
		$pdf->myCell($width[0], $height[1], '', 'LR');
	if($printdpt == 0  && $newpage != 0 && ($d-$c) == 13)
		$pdf->myCell($width[0], $height[1], '', 'LR');
		$pdf->myCell($width[1], $height[1], $curdpt, 'LR');
		$pdf->SetFont('Helvetica', '', 7);
		$pdf->myCell($width[2], $height[1], '  '.$k, 'LR');
		$pdf->SetFont('Helvetica', '', 14);*/
	$newpage = 0;
		$dpt_hal[$a][] = $hall_no;
		//echo $a.'is dept and hall is'.$hall_no.'<br>';
		$curdpt_no = count($dpt_hal[$a]);
	disp_dpt( $b[$a], $c, $d , $a);
  }
}
$symbol; //stores the symbol which stays beside the regnos using nxtchk() function
$hyprint; //stores the temp value to notify that hyphen as already been placed
function disp_dpt($reg, $a, $total, $dept)//used by disp_rqd() and disp_full() functions
{	//this is the function which places the comma, hyphen and period where-ever necessary
	global $print_hal, $curhal, $pdf, $width, $height, $curdpt, $curtotal, $curdpt_no, $hall_no, $hallwise, $dptinc_glbl;
	//global $w, $strmax, $tempreg, $printd_no, $firstloop, $symbol, $hyprint;
	//$in, $n, $temp104;
	$curdpt = dpt($dept);
	//$dptinc_glbl;//1 represents new dept for hallwiser
	$print_dpt = 1;
	$tempreg; $print = 0; $islast = 0;
	$first_line = 1;
	$curtotal = $total - $a;
	//echo 'total = '.$total.' a = '.$a.' & real total = <b>'.($total - $a).'</b><br>';//for check!!!!
	$tmpcounter = 0;//if this reaches 3 then the 3 reg nos are printed
	$eq_b4 = -1;//if -1 remains then only one number is being printed
	
	//UPDATE 2014.06.24//
	//UPDATE 2014.07.19//
	$tmpcounter = 0;
	$equal = 0;
	$incr = 0;
	//$position = 'first';
	$tempreg = '';
	$skipp = 'off';
	
	for( $i = $a; $i < $total; $i++ )
	{
	if(isset($reg[$i]))
	{
		{//this block stores the register numbers in an array hallwise
			hallwiser($reg[$i], $dept);
			//$dp/tinc_glbl = 0;
		}
	
		intval($reg[$i]);
		$p = $reg[$i];//present (current) used reg no.
		$in = $reg[$i] + 1;//imaginary next reg no. adds one to $p
		$inn = $reg[$i] + 2;
		/*/$wait = 1000;
		  //echo 'entering at i='.$i.' '.$p.'<br>';// for check!!!!
		//$printed = 'no';
		/if($tmpcounter == 0)
		{
			$tempreg = ''.$p;
			$tmpcounter += 1;
			$printed = 'yes';/*
			if($i != $a)
				$center = 'center';
		} elseif ($center == 'center') {
			$tempreg = ', '.$p;
			$center != 'not';/
			$somemore = 'notanymore';
		} elseif ( ($i + 1) == $total ) {
			goto notequallabel;
		}
		
		if($somemore == 'work'){
			$tempreg .= ', '.$p;//.'c='.($tmpcounter+1);
			$tmpcounter += 1;
			$somemore = 'notanymore';
		}elseif( isset($reg[$i+1]) ){
			$rn = $reg[$i+1];
			$equal = nxtchk($rn, $in);
			if($equal == 1){
				$incr += 1;
			} elseif($equal == 0 && $tmpcounter != 0) {
				notequallabel:
				if($printed != 'yes' && $tmpcounter == 1 && $incr == 0) {
					$tempreg .= ', '.$p;
				} elseif($printed != 'yes' && $tmpcounter == 1 && $incr == 1) {
					$tempreg .= ', '.$p;
				} elseif($printed != 'yes' && $incr == 0) {
					$tempreg .= ', '.$p;
				} elseif($printed != 'yes' && $incr == 1) {
					$tempreg .= ' - '.$p;
				} elseif($incr > 1) {
					$tempreg .= ' - '.$p;
				}
				
				$tmpcounter += 1;
				if($tmpcounter == 2){
					$somemore = 'work';
					if($printed == 'yes')
						$tmpcounter = 1;
				}
				$incr = 0;
			}
			
			if($printed != 'yes'){
				$printed = 'yes';
			}
		}*/
		
		if( ($i+1) == $total ){
			$tempreg .= $p.'.';
			goto printlabel;
		} elseif($skipp != 'on'){
			if($equal == 0){
				$tempreg .= $p;
				$tmpcounter = $tmpcounter + 1;
				$skipp = 'off';
				if($tmpcounter == 3)
					goto printlabel;
			}
			if(isset($reg[$i+1]) && $reg[$i+1] == $in)
			{
				$equal = $equal + 1;
				if(isset($reg[$i+2]) && $reg[$i+2] == $inn)
				{
					$equal = $equal + 1;
					if($equal == 2) {
						$tempreg .= ' - ';
						$skipp = 'on';
					}
				} else {
					if($equal == 1)
						$tempreg .= ', ';
					$equal = 0;
				}
			}
			elseif($skipp == 'oner')
			{
				$equal = 0;
				$skipp = 'off';
				$tempreg .= $p;
				$tmpcounter = $tmpcounter + 1;
				if ($tmpcounter == 2)
					$tempreg .= ', ';
			}
			else
			{
				$tempreg .= ', ';
				$equal = 0;
				$skipp = 'off';
			}
		} elseif ($skipp == 'on'){
			$skipp = 'oner';
		}
		
		if($tmpcounter == 3){
			printlabel:
			if( ($i+1) != $total)
				$tempreg .= ',';
			$printreg = $tempreg;
			$tempreg = '';
			$tmpcounter = 0;
			$print = 1;
		}
		
		/*
		$curhal 	for current hall			$print_hal
		$printreg 	for regnos in current line
		$curdpt		for department name			$print_dpt
		$curtotal	for total dept wise			$print_dpt
		*/
		//$prev = $p; NOT USED IN UPDATE 2014.06.24
		if($print == 1){
			/*if($print_hal == 1) {
				echo '<b>'.$curhal.'</b><br>';//for check!!!!
				$print_hal = 0;
			}
			if($first_line == 1) {
				echo 'Dept: '.dpt($dept).' Total: '.$curtotal.'<br>';
				$first_line = 0;
			}
			echo ''.$printreg.'<br>';
			$print = 0;*/ //for GREAT CHECK!
			
			
			if($print_hal == 1){//prints hall name
				$pdf->SetFont('', 'B');
				$pdf->myCell($width[0], $height[1], $curhal, 'TLR');
				$pdf->SetFont('');
				add('h', 1);
				$print_hal = 0;
			}
			else{//prints blank cell instead of hall name
				$pdf->myCell($width[0], $height[1], '', 'LR');
			}
			
			if($print_dpt == 1){//prints dept name and total
				$pdf->myCell($width[1], $height[1], $curdpt, 'TLR');
				$pdf->SetFont('Helvetica', '', 7);
				$pdf->myCell($width[2], $height[1], '        '.$curtotal, 'TLR');
				$pdf->SetFont('Helvetica', '', 14);
				$print_dpt = 0;
				
				/*//department numbering
				$pdf->Cell($width[2], $height[2], '  ', 'TLR');
				$pdf->Ln();
				$pdf->SetFont('Helvetica', 'I', 9);
				$pdf->Cell($width[0], $height[1], '', 'TLR');
				$pdf->Cell($width[1], $height[1], "set".$curdpt_no, 'TLR', 0, 'R');
				$pdf->Cell($width[1], $height[2], '', 'TLR');
				$pdf->Ln();
				$pdf->Cell($width[0], $height[1], '', 'TLR');
				$pdf->Cell($width[1], $height[1], '', 'TLR');
				$pdf->Cell($width[2], $height[1], '', 'TLR');
				$pdf->SetFont('Helvetica', '', 14);
				*/
			}
			else{//places blanks for dept name and total
				$pdf->myCell($width[1], $height[1], '', 'LR');
				$pdf->myCell($width[2], $height[1], '', 'LR');
			}
			
			
			if($first_line == 1){
				$pdf->myCell($width[3], $height[1], $printreg, 'TLR');
				$first_line = 0;
			}
			else
				$pdf->myCell($width[3], $height[1], $printreg, 'LR');
			$print = 0;
			$pdf->Ln();
		}
	}
	}
}

function nxtchk($in, $n)
{
	global $tempreg, $symbol, $w, $hyprint;
	if( $n == $in )
		return 1;
	else
		return 0;
}

/*function disp_dpt($reg, $a, $total) //used by disp_rqd() and disp_full() functions
{
   global $w, $strmax, $tempreg, $printd_no, $firstloop;
	$in = null; $n = null; $pcountr=0; $firstloop = 1; //a is the starting number from which number to display.
   for($i=$a; $i < $total; $i++)  
 { 
  if (isset($reg[$i]))
  {
    intval($reg[$i]);
    $p = $reg[$i]; //present (current) used reg no.
 	$in = $reg[$i] + 1; // imaginary next reg no. adds one to $p
	if (isset($reg[$i+1]))
	{
	  intval($reg[$i+1]);
	  $n = $reg[$i+1]; // this is the real next reg no. present in the array
	}
	if ($i==$a)
	{
	  $tempreg = $reg[$i];
	  echo $reg[$i].'first';
	  //$pdf->myCell($width[2], $height[1], $reg[$i], 'LR');
	  nxtchk($in, $n, 0, $p);
	}
	elseif ($i == ($total-1))
	{
	  if($w==0 or $w==1)
	  {
		if(strlen($tempreg.', '.$reg[$i])<$strmax){
			$tempreg .= ', '.$reg[$i]; $pcountr = 1;
			$printd_no = $reg[$i]+1;}
		else
		{
			$tempreg .= ',';
			if($firstloop == 1){
			//printcell1($tempreg); now
			$firstloop = 0;}
			else
			//printcell($tempreg); now
			$tempreg = $reg[$i];
			$pcountr = $pcountr + 1;
		}
	  }
	  elseif($w>1)
	  {
		if(strlen($tempreg.' - '.$reg[$i])<$strmax)
		{
			$tempreg .= ' - '.$reg[$i]; $pcountr = 1;}
		else
		{
			$tempreg .= ',';
			if($firstloop == 1){
			//printcell1($tempreg); now
			$firstloop = 0;}
			else
			//printcell($tempreg); now
			$tempreg = $printd_no.' - '.$reg[$i];
			$pcountr = $pcountr + 1;
		}
		//$pdf->myCell($width[2], $height[1], ' - '.$reg[$i], 0);
		}
	}
	elseif($w==0)
	{
	  echo ", ".$reg[$i];
		if(strlen($tempreg.', '.$reg[$i])<$strmax){
			$tempreg .= ', '.$reg[$i]; $pcountr = 1;
			$printd_no = $reg[$i]+1;}
		else
		{
			$tempreg .= ',';
			if($firstloop == 1){
			//printcell1($tempreg); now
			$firstloop = 0;}
			else
			//printcell($tempreg); now
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
			//printcell1($tempreg); now
			$firstloop = 0;}
			else
			//printcell($tempreg); now
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
    if($s==1)
	{
      echo ", ".$a;
		if(strlen($tempreg.', '.$a)<$strmax)
		{
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
			$tempreg = $a;
		}
	  //$pdf->myCell($width[2], $height[1], ', '.$a, 0);
	  }
    if($s==2){
	  echo " - ".$a;
		if(strlen($tempreg.' - '.$a)<$strmax){
			$tempreg .= ' - '.$a;}
		else
		{
			$tempreg .= ',';
			if($firstloop == 1){
			printcell1($tempreg);
			$firstloop = 0;}
			else
			printcell($tempreg);
			$tempreg = $printd_no.' - '.$a;
		}
	  //$pdf->myCell($width[2], $height[1], ' - '.$a, 0);
	  }
  }
}
*/
$hall_no;
$arr = '';//stores 'arr' and 'sub' when being in the functions
$its13 = 0;
function arrange()
{
	global $ttlb, $ttli, $ttlt, $totalt, $totali, $totalb, $b, $e, $nodept, $width, $header, $pdf, $width, $height, $printdpt, $curhal, $print_hal;
	global $allhalls, $hall_no, $arr;
	$pdf->SetFont('Helvetica','',14);
	$pdf->AddPage();
	$pdf->ImprovedTable($header, $width, $height);
	//$a = 1; $m; $retry = 0; $e12=1; $e13 = 1;
	$temp78 = 0; $n = 0;//local $temp88
		$ttlt = counting('t');
		$ttli = counting('i');
	$hallcounter = -1;
	add('r', $ttlb);
	while ($ttli < $ttlb)
	{
		//echo '<br>beginning $ttli = '.$ttli.' $ttlb = '.$ttlb.'<br>';//for check!!!
		//echo '<br><b>New Hall:</b><br>';//for check!!!
		$hallcounter = $hallcounter + 1;//counts the hall number
		$curhal = $allhalls[$hallcounter];//[$hallcounter]; //saves current hall inside $curhal for pdf use
		$hall_no = $hallcounter;
		$print_hal = 1;
		$arr = 'arr';
		//echo '<b>'.$curhal.'</b><br>';//for check!!!!
		for( $z = 0; $z < 90; $z = nextdpt($z) )
			$totalt[$z] = $totalb[$z] - $totali[$z];
		$ttlt = counting('t');
		$ttli = counting('i');
		if($e == 12 || $e == 0)
			$e = 13;
		elseif($e == 13)
			$e = 12;
		if($ttlb <= 25)//if total is LESS THAN OR EQUAL TO 25
		{
			for( $z = 0; $z < 90; $z = nextdpt($z) )
				if( $totalt[$z] != 0 )
					ezar($z, $totalt[$z]);
		}
		elseif($e == 13)// for 13 values, if no dept contains sufficient values n=1
		{
			for( $z = 0; $z < 90; $z = nextdpt($z) )
				if( $totalt[$z] > 14 || $totalt[$z] == 13 )
				{
					//echo '<br>forcing ezar in 13<br>';//for check!!!
					ezar($z, 13);
					//echo '<br>forcing sub_ar in 13<br>';//for check!!!
					sub_ar($z, 12);
					//echo '<br>end sub_ar<br>';//for check!!
					$temp78 = 1;
					break;
				}
			if($temp78 != 1)//if no dept contains 13
			{
				if($n != 2)//if dept contains 12
					$n = 1;
				else//if not even 1 dept contains 12
					$n = 2;
			}
			$temp78 = 0;
		}
		elseif($e == 12)// for 12 values, if no dept contains sufficient values n=2
		{
			for( $z = 0; $z < 90; $z = nextdpt($z) )
				if( $totalt[$z] > 13 || $totalt[$z] == 12 )
				{
					ezar($z, 12);
					//echo 'hi sub<br>';//used to check whether an error has occured within this fn or sub_ar()
					sub_ar($z, 13);
					//echo 'bye sub';//same as above
					$temp78 = 1;
					break;
				}
			if( $temp78 != 1 )
				$n = 2;
			$temp78 = 0;
		}
		if($n == 1)//comes from e=13, so checks for 12 values, if no dept contains then n=2
		{
			for( $z = 0; $z < 90; $z = nextdpt($z) )
				if( $totalt[$z] > 13 || $totalt[$z] == 12 )
				{
					ezar($z, 12);
					sub_ar($z, 13);
					$temp78 = 1;
					break;
				}
			if( $temp78 != 1 )
				$n = 2;
			else
				$n = 0;
			$temp78 = 0;
		}
		if($n == 2)//if none of the above acts, then this is done for all 25..!
		{
			$temp78 = 0;
			for( $z = 0; $z < 90; $z = nextdpt($z) )
			{
				$temp88 = $temp78 + $totalt[$z];
				if($temp88 < 26 && $temp88 != 24 && $totalt[$z] != 0)
				{
					$temp78 = $temp78 + $totalt[$z];
					ezar($z, $totalt[$z]);
				}
			}
			if( $temp78 <= 24 )//if 25 is not reached
			{
				//find large and get the rest
				//$z = large($z, 1)
				$temp88 = 25 - $temp78;
				//echo '$temp88 '.$temp88; //for check!!!!!!
				for( $z = 0; $z < 90; $z = nextdpt($z) )
				{
					$temp104 =  $totalt[$z] - $temp88;
					if($temp104 != 1 && $totalt[$z] != 0)
					{
						$temp78 = 25;
						ezar($z, $temp88);
						break;
					}
				}
			}
			$temp78 = 0;
			$n = 0;
		}
		//echo 'end $ttli = '.$ttli.' $ttlb = '.$ttlb.'<br><br>'; // for check!!!!
	}
}
function sub_ar($c, $a)
{
	global $f, $totali, $totalt, $totalb, $ttlb, $ttlt, $ttli, $nodept, $pdf, $width, $height, $header, $arr, $its13;
	$n = 0; $temp78 = 0; $temp88 = 0;// $m = 0; $proceed = 0;
	//echo '<br>entering sub_ar<br>';//forcheck!!!!!
	$arr = 'sub';
	if($a == 12)//for arranging 12 nos
	{
		for( $z = 0; $z < 90; $z = nextdpt($z) )//regular arrangment which searches for 12 reg nos in other dept
			if( ($z != $c) && ($totalt[$z] > 11) && ($totalt[$z] != 13) )
			{
				ezar($z, $a);
				$n = 1;
				break;
			}
		if($n != 1)
		{
			for( $z = 0; $z < 90; $z = nextdpt($z) )//when no dept contains 12 reg nos
			{
				$temp88 = $temp78 + $totalt[$z];
				if($temp78 < $a && $totalt[$z] != 0 && $temp88 != 1 && $temp88 <= $a && $z != $c)//combination of 12 reg nos.
				{
					$temp78 = $temp78 + $totalt[$z];
					ezar($z, $totalt[$z]);
				}
				elseif($temp78 == $a)//for speed processing
					break;
				//echo '<br>'.$temp78.' and a is '.$a.'<br>';//for check!!!!
			}
			if($temp78 != $a && $totalt[$c] == 0)
			{
				$temp88 = $a - $temp78;
				for( $z = 0; $z < 90; $z = nextdpt($z) )//if combi doesnot have 12 res nos, tries to take in other dept
				{
					if( $totalt[$z] >= $temp88 && ($totalt[$z] - $temp88) != 1 && $temp88 != 1)
					{
						$temp78 = $a;
						//echo 'not fulfilled at 12 and temp = '.$temp88;//for check!!!
						ezar($z, $temp88);
						break;
					}
				}
			}
			elseif($temp78 != $a && $totalt[$c] != 0)//if combination does not have 12 reg nos, takes rest in same dept
			{
				$temp88 = $a - $temp78;
				if($temp88 > $totalt[$c])//if same dept doesnot contain sufficient reg nos
					$temp88 = $totalt[$c];
				ezar($c, $temp88);
				$temp78 = $a;
			}
		}
	}
	elseif($a == 13)//for arranging 13 nos
	{
		$its13 = 1;
		for( $z = 0; $z < 90; $z = nextdpt($z) )//regular arrangment which searches for 13 reg nos in other dept
			if( ($z != $c) && ($totalt[$z] > 12) && ($totalt[$z] != 14) )
			{
				ezar($z, $a);
				$n = 1;
				break;
			}
		if($n != 1)
		{
			for( $z = 0; $z < 90; $z = nextdpt($z) )//when no dept contains 13 reg nos
			{
				$temp88 = $temp78 + $totalt[$z];
				if($temp78 < $a && $totalt[$z] != 0 && $temp88 != 1 && $temp88 <= $a && $z != $c)//combination of 13 reg nos.
				{
					$temp78 = $temp78 + $totalt[$z];
					ezar($z, $totalt[$z]);
				}
				elseif($temp78 == $a)//for speed processing
					break;
			}
			if($temp78 != $a && $totalt[$c] == 0)
			{
				$temp88 = $a - $temp78;
				for( $z = 0; $z < 90; $z = nextdpt($z) )//if combi doesnot have 13 res nos, tries to take in other dept
				{
					if( $totalt[$z] >= $temp88 && ($totalt[$z] - $temp88) != 1  && $temp88 != 1)
					{
						$temp78 = $a;
						//echo 'not fulfilled at 13 and temp = '.$temp88;//for check!!!
						ezar($z, $temp88);
						break;
					}
				}
			}
			elseif($temp78 != $a && $totalt[$c] != 0)//if combination does not have 13 reg nos, takes rest in same dept
			{
				$temp88 = $a - $temp78;
				if($temp88 > $totalt[$c])//if same dept doesnot contain sufficient reg nos
					$temp88 = $totalt[$c];
				ezar($c, $temp88);
				$temp78 = $a;
			}
		}
	}
}

$final_hall = 0;
$final_reg = 0;
function add($text, $number){
	global $final_hall, $final_reg;
	if($text == 'h')//for counting halls
		$final_hall = $final_hall + $number;
	elseif($text == 'r')//for counting register numbers
		$final_reg = $final_reg + $number;
}

//$inc = 0;//not used anywhere
function ezar($i, $j)//the function which gets the number from arrange() and sub_ar() functions
{
	global $totali, $totalt, $totalb, $ttli, $ttlt, $dptinc_glbl; //, $hallwise, $hall_no;
	$dptinc_glbl = 1;
	disp_rqd($i, $totali[$i], $totali[$i]+$j);
	$totali[$i] = $totali[$i] + $j;
	$totalt[$i] = $totalb[$i] - $totali[$i];
	if($totalt[$i] < 0)
		$totalt[$i] = 0;
	$ttli = counting('i');
	$ttlt = counting('t');
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
function detailsprint()
{
	global $dpt_hal, $pdf, $allhalls, $final_hall, $final_reg;
	$width = array(37, 150, 30, 7);
	$height = array(8, 7, 0);
	$hall;
	$pdf->AddPage();
	$pdf->SetFont('Helvetica', 'BU', 14);
	$pdf->Cell(0, '10', 'All Exam-Halls (course-wise)', '', 0, 'C');
	$pdf->Ln();
	$pdf->SetFont('', 'BI', 14);
	$pdf->Cell($width[0], $height[0], 'Deg./Dept.', 'BTLR', 0, 'C');
	$pdf->Cell($width[1], $height[0], 'Exam-Halls', 'BTLR', 0, 'C');
	$pdf->Ln();
	$pdf->SetFont('', '');
	for($z = 0; $z < 90; $z = nextdpt($z))
	{
	  $hall = ' ';
	  if(isset($dpt_hal[$z])){
		$pdf->Cell($width[2], $height[1], dpt($z), 'L');
		$m = count($dpt_hal[$z]);
		$pdf->SetFont('', 'BI', 8);
		$pdf->Cell($width[3], $height[1], $m, 'R', 0, 'R');
		$pdf->SetFont('', '', 14);
		$j = 0;
		$k = 0;
		$m = 0;
		$sym = ', ';
		foreach($dpt_hal[$z] as $i){
			$m = $m + 1;//manual increment for the halls
			$k = $k + 1;//calculation as a whole
			if(!isset($dpt_hal[$z][$k]))
				$sym = '.';
			$l = strlen($allhalls[$i]);//stores length of hall name
			$j = $j + $l;//calculation for every line
			if ( isset($dpt_hal[$z][$k]) )
				$j = $j + 1;
			$hall .= $allhalls[$i].$sym;
			if($j >= 47 && isset($dpt_hal[$z][$m]) ){
				$pdf->Cell($width[1], $height[1], $hall, 'LR');
				$pdf->Ln();
				$pdf->Cell($width[0], $height[1], '', 'LR');
				$hall = ' ';
				$j = 0;
				$m = 0;
			}
		}
		$pdf->Cell($width[1], $height[1], $hall, 'BLR');
		$pdf->Ln();
	  }
	}
	$pdf->Cell($width[0], $height[2], '', 'BLR');
	$pdf->Cell($width[1], $height[2], '', 'BLR');
	
	$pdf->Ln();
	$pdf->SetFont('Times', 'I', 15);
	$spacer = '                                                                         ';
	$details = 'Total Candidates: '.$final_reg.' '.$spacer.' Total Halls: '.$final_hall;
	$pdf->Cell(0, '10', $details, '', 0, 'C');
}

//function 
//mainarranger('none'); its already initiated in the test session section at the beginning.
//$dptinc_next;
$dpt_key;
$firsttime = 1;//is used in hallwiser() for first time
function hallwiser($a, $dptno){
	global $arr, $hall_no, $its13, $dptinc_glbl, $hallwise, $dpt_key, $firsttime;
	if($dptinc_glbl == 1) {
	  if($firsttime == 1) {
		$hallwise [$hall_no][0][0] = $dptno;
		$hallwise [$hall_no][0][1] = $a;
		$firsttime = 0;
		$dpt_key = 0;
	  } else {
		$hallwise [$hall_no][][]=$dptno;
		for($i = 0; $i < count($hallwise[$hall_no]); $i++) {
			if( array_search($dptno, $hallwise[$hall_no][$i]) == 0 )//$key = array_search($element, $array);
				$sub_key = $i;
		}
		$dpt_key = $sub_key;
		$hallwise [$hall_no][$dpt_key][]=$a;
	  }
		$dptinc_glbl = 0;
	} else {
		$hallwise [$hall_no][$dpt_key][]=$a;
	}
}

function hallwise(){
	global $hallwise, $hall_no, $allhalls, $pdf;
	$order = array( array() ); 
	$mixhall = array( array() );
	for($i = 0; $i < count($hallwise); $i++){	//loops this for single hall
	   $over = 0;
		$order[$i] = orderhall($hallwise[$i]);
		$mixhall[$i] = mixhall($hallwise[$i], $order[$i]);//mixed single hall
		designer($mixhall[$i], $hallwise[$i], $allhalls[$i]);
		//if($i+1 == count($hallwise))
		  //print_r($mixhall[$i]);
	}
	//$pdf->AddPage();
	//$pdf->SetFont('Helvetica', '', 14);
}

function largehall($input){//not used anywhere
	$large_ttl; $large_dpt;
	$large_ttl = 0;
	for($i = 0; $i < count($input); $i++){
		$counter = count($input[$i]);
		if( $large_ttl < $counter ){
			$large_ttl = $counter;
			$large_dpt = $i;
		}
	}
	return $large_dpt;
}

function orderhall($a){//returns the order of an array counting its sub elements
/*	$size_un = array( array() );
	for($i = 0; $i < count($a); $i++){
		$c = count($a[$i]);
		$c = $c - 1;
		$size_un[$i][$c] = $i;
	}
	echo'

normal
------
';
	print_r($size_un);
	$size = $size_un;
	for($k = 0; $k < count($size); $k++){
		$i = array_search($k, $size[$k]);
	for($m = 0; $m < count($size); $m++){
		$j = array_search($m, $size[$m]);
		if($i < $j && $k != $m && $i != null && $j != null){
			$num1 = $size[$k][$i];
			$num2 = $size[$m][$j];
			unset($size[$k][$i]);
			unset($size[$m][$j]);
			$size[$k][$j] = $num2;
			$size[$m][$i] = $num1;
			$i = array_search($k, $size[$k]);
		}
	}
	}
	echo '
size is below
-------------
';
	print_r($size);
//$size is arranged in descending order
	$order = array();
	for($i = 0; $i < count($size); $i++)
	for($j = 0; $j < count($size); $j++){
		$key = array_search($i, $size[$j]);
		if($key != null)
			$order[$i] = $j;
	}
	$correct = array();
	for($j = 0; $j < count($order); $j++)
	for($i = 0; $i < count($order); $i++){
		$num = $order[$i];
		if($num == $j)
		$correct[$num] = $i;
	}
	return($correct);*/

	for($i = 0; $i < count($a); $i++){
		$c = count($a[$i]);
		$c = $c - 1;
		$size_un[$i][$c] = $i;
	}

	for($k = 0; $k < count($size_un); $k++){
		$i = array_search($k, $size_un[$k]);
		$size_key[$k] = $i;
		$size_num[$k] = $k;
	}

$n = count($a);
for($k = 0; $k < $n; $k++){
  for($j = 0; $j < $n; $j++){
	if($size_key[$k] > $size_key[$j]) {
	  $t = $size_key[$k];
	  $size_key[$k] = $size_key[$j];
	  $size_key[$j] = $t;
	
	  $t = $size_num[$k];
	  $size_num[$k] = $size_num[$j];
	  $size_num[$j] = $t;
	}
  }
}

return($size_num);
}

function mixhall($hall, $order){
	$mix = array();
	$nor = array();
	$total = 0;
	for($i = 0; $i < count($hall); $i++){//looped for an hall
		$k = $order[$i];
		$tot = count($hall[$k]);
		$total = $total + $tot - 1;
			//echo 'tot = '.$tot;
		for($j = 1; $j < $tot; $j++){// arranging department wise
			$nor[] = $hall[$k][$j];
		}//normal has been formed
	}
			//echo 'total = '.$total;
	//$table = array();
	//$table2 = array();
	for($i = 0; $i < 25; $i++){
			//echo 'i = '.$i;
		/*if( $i < 13 ){
			$j = (2*$i);
			//$table[$i] = $j;
			//$table2[$i] = ($i/2);
			$mix[$j] = $nor[$i];//$mix[$i] = $nor[($i/2)];
		}else{
			$j = (($i - 12) + ($i - 13));
			//$table[$i] = $j;
			//$table2[$i] = (12 + (($i+1)/2));
			$mix[$j] = $nor[$i];//$mix[$i] = $nor[(12 + (($i+1)/2))];
		}*/
		$j = nxt($i);
		if( isset($nor[$i]) )
			$mix[$j] = $nor[$i];
		else
			$mix[$j] = '';
	}//mix has been formed   |^| above is 12 because we will have total of 25
	return $mix;
}

function nxt($a){//function which is used to find the mix number for student in an hall used in the above function only
  switch($a){
	case 0: $j = 0; break;
	case 1: $j = 10; break;
	case 2: $j = 20; break;
	case 3: $j = 6; break;
	case 4: $j = 16; break;
	case 5: $j = 2; break;
	case 6: $j = 12; break;
	case 7: $j = 22; break;
	case 8: $j = 8; break;
	case 9: $j = 18; break;
	case 10: $j = 4; break;
	case 11: $j = 14; break;
	case 12: $j = 24; break;
	case 13: $j = 5; break;
	case 14: $j = 15; break;
	case 15: $j = 1; break;
	case 16: $j = 11; break;
	case 17: $j = 21; break;
	case 18: $j = 7; break;
	case 19: $j = 17; break;
	case 20: $j = 3; break;
	case 21: $j = 13; break;
	case 22: $j = 23; break;
	case 23: $j = 9; break;
	case 24: $j = 19; break;
  }
  return $j;
}

function designer($nos, $hall, $hallname){
	global $pdf, $allhalls;
	$height = array(0, 7, 9, 10);
	$width = array(0, 4, 6, 9, 13, 30, 2);
	$width2 = array(0, 12, 27, 145);
	$height2 = array(0, 5, 7);
	$pdf->AddPage();
	$pdf->Cell(0, 2);
	$pdf->Ln();
	$pdf->SetFont('Times', 'BU', 17);
	$pdf->Cell(0, 6, 'Hall No: '.$hallname, '', 0, 'C');
	$pdf->Ln();
	$pdf->SetFont('Helvetica', '', 11);
	//$pdf->Cell(width, height, data, border, 0, align)
	
	//set starts
	$pdf->Ln();
	$count = -1;
	for($j = 0; $j < 5; $j++){
	for($i = 0; $i < 5; $i++){
	$pdf->Cell($width[6], $height[1]);
	switch($i){
	 case 0: $tbl = 'A'; break;
	 case 1: $tbl = 'B'; break;
	 case 2: $tbl = 'C'; break;
	 case 3: $tbl = 'D'; break;
	 case 4: $tbl = 'E'; break;
	}
	$i1 = $i + 1;
	$j1 = $j + 1;
	$tableno = $tbl.''.($j1);
	$pdf->Cell($width[1], $height[1]);
	$pdf->SetFont('', '');
	$pdf->Cell($width[4], $height[1], 'Table:', 'LT', 0, 'R');
	$pdf->SetFont('', 'B');
	$pdf->Cell($width[3], $height[1], $tableno, 'TR', 0, 'L');
	//$pdf->SetFont('', '');
	$pdf->Cell($width[1], $height[1]);
	$pdf->Cell($width[2], $height[1]); }
	//table ends
	$pdf->Ln();
	for($i = 0; $i < 5; $i++){
	if($j%2 == 0 && $i%2 == 1)
		$pdf->SetFont('', 'IU');
	elseif($j%2 == 1 && $i%2 == 0)
		$pdf->SetFont('', 'IU');
	else
		$pdf->SetFont('', '');
	$pdf->Cell($width[6], $height[1]);
	if(isset($nos[$count + 1])){
	  $count = $count + 1;
	  $continue = $nos[$count];
	} else {
	  $continue = '';
	  $count = $count + 1;
	}
	//if($continue == 0)
	  $pdf->Cell($width[5], $height[2], $continue, 'BLTR', 0, 'C');
	//else
	  //$pdf->Cell($width[5], $height[2], '', 'BLTR', 0, 'C');
	$pdf->Cell($width[2], $height[1]);}
	//set ends
	$pdf->Ln();
	$pdf->Ln();
	}
	$pdf->Cell($width[6], $height[1]);
	$pdf->SetFont('', 'IU', 14);
	$pdf->Cell($width2[2], $height2[2], 'Deg./Dept.', 'LT', 0, 'C');
	$pdf->Cell($width2[1], $height2[2], 'Total', 'LT', 0, 'C');
	$pdf->Cell($width2[3], $height2[2], 'Reg Nos.', 'LTR', 0, 'C');
	$pdf->Ln();
	$pdf->SetFont('', '', 11);
	for($i = 0; $i < count($hall); $i++){
	  $pdf->SetFont('', 'B');
	  $dept = dpt($hall[$i][0]);
	  $pdf->Cell($width[6], $height[1]);
	  $pdf->Cell($width2[2], $height2[1], $dept, 'LT', 0, 'C');
	  $pdf->Cell($width2[0], $height2[0]);
	  $pdf->SetFont('', '');
	  $pdf->Ln();
	  $string = array( array() );
	  unset($string[0]);
	  $nu = 0;
	  $line = 0;
	  $string[$line] = '  ';
	  //$r = 0;
	  for($j = 1; $j < count($hall[$i]); $j++){
	    //norespect:
		$nu = $nu + 1;
		if (($j+1) == count($hall[$i]) ){
		  $string[$line] .= $hall[$i][$j].'.';
		  $nu = 9;
		}else
		  $string[$line] .= $hall[$i][$j].', ';
		
		if($nu == 5){
		  $line = $line + 1;
		  $string[$line] = '  ';
		  $nu = 0;
		}
		/*
		if($nu == 9){
		}*/
	  }
		  for($r = 0; $r < count($string); $r++){
			if($r == 0)
				$bord = 'LTR';
			elseif($r == (count($string) - 1))
				$bord = 'BLR';
			else
				$bord = 'LR';
			$pdf->Cell($width[6], $height2[2]);
			$pdf->Cell($width2[2], $height2[1], '', $bord);
			if($r == 0){
			  $CHERE = ' '.(count($hall[$i]) - 1);
			  $pdf->Cell($width2[1], $height2[1], $CHERE, $bord, 0, 'R'); }
			else
			  $pdf->Cell($width2[1], $height2[1], ' ', $bord);
			$pdf->Cell($width2[3], $height2[1], $string[$r], $bord, 0, 'L');
			$pdf->Ln();
		  }
		 //print_r($string);
	}
	$pdf->Cell(0, 0);//to put border
	$pdf->Ln();
	$pdf->Cell($width[6], $height2[2]);
	$pdf->Cell(($width2[1] + $width2[2] + $width2[3]), 0, '', 'B');
	$dept_tot = count($hall);
	$ovrl_tot = 0;
	for($i = 0; $i < $dept_tot; $i++)
		$ovrl_tot = $ovrl_tot + count($hall[$i]) - 1;
	//$desc = 'Overall: '.$ovrl_tot.' candidates from '.$dept_tot.' depts';
	//if($dept_tot != 1)
	//else
		//$desc = 'Overall Total: '.$ovrl_tot.' from '.$dept_tot.' dept';
	$pdf->SetFont('', 'I', 11);
	//$pdf->Cell(20, $height2[2]);
	//$pdf->Cell(20, 4, 'Overall //');
	$pdf->Cell(0, 1);
	$pdf->Ln();
	$pdf->Cell(10, $height2[2]);
	$pdf->SetFont('', 'IU');
	if($dept_tot != 1)
		$pdf->Cell(13, 4, 'Depts:');
	else
		$pdf->Cell(15, 4, 'Dept(s):');
	$pdf->SetFont('', 'B');
	$pdf->Cell(10, 4, $dept_tot);
	
	$pdf->SetFont('', 'IU');
	$pdf->Cell(12, 4, 'Total:');
	$pdf->SetFont('', 'B');
	$pdf->Cell(20, 4, $ovrl_tot);
	//$pdf->Ln();
	//$pdf->Cell(20, $height2[2]);
	$pdf->Cell(5, 15);
	$pdf->Ln();
	$pdf->SetFont('', '', 13);
	$pdf->Cell($width[6], $height2[2]);
	$pdf->Cell(170, 7, 'Chief Superintendent', '', 0, 'R');
}

$pdf = new PDF();


if($mainarranger == 'none'){
mainarranger('none');
$cursession = $sessionReal;
arrange();
for($i=0;$i<count($header);$i++)
	$pdf->myCell($width[$i], $height[2], '', 'BLR');
detailsprint();
$firsttime = 1;
hallwise();} elseif($sessionReal == 'FN') {
mainarranger('FN');
$cursession = 'FN';
arrange();
for($i=0;$i<count($header);$i++)
	$pdf->myCell($width[$i], $height[2], '', 'BLR');
detailsprint();
$firsttime = 1;
hallwise();
//designer();
} elseif($sessionReal == 'AN') {
mainarranger('AN');
$cursession = 'AN';
arrange();
for($i=0;$i<count($header);$i++)
	$pdf->myCell($width[$i], $height[2], '', 'BLR');
detailsprint();
$firsttime = 1;
hallwise();} elseif($sessionReal == 'FNAN') {
mainarranger('FN');
$cursession = 'FN';
arrange();
for($i=0;$i<count($header);$i++)
	$pdf->myCell($width[$i], $height[2], '', 'BLR');
detailsprint();
$firsttime = 1;
hallwise();
mainarranger('AN');
$cursession = 'AN';

	unset($dpt_hal);
	$dpt_hal = array();
	$final_hall = 0;
	$final_reg = 0;
	$allhalls = $sessioneHalls2;
	unset($hall_no);
	$hall_no;
	unset($hallwise);
	$hallwise = array( array( array() ) );

arrange();
for($i=0;$i<count($header);$i++)
	$pdf->myCell($width[$i], $height[2], '', 'BLR');
detailsprint();
$firsttime = 1;
hallwise();}

$pdf->Output($pdfName, 'I');

end:
if ($error == 1)
  header('Location: blunder.php');

?>