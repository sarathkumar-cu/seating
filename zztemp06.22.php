<?php


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
	$tmpcounter = 1;//if this reaches 3 then the 3 reg nos are printed
	$eq_b4 = -1;//if -1 remains then only one number is being printed
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
		$wait = 1000;
		  //echo 'entering at i='.$i.' '.$p.'<br>';// for check!!!!
		if( ($i + 1) != $total && isset($reg[$i + 1]) )
		{
		  intval($reg[$i + 1]);
		  $n = $reg[$i + 1];
		  if($i == $a)//for first value which prints the number directly without hesitation :)
		  {
			$tempreg = $p;
			$w = 0;
			$tmpcounter = 1;
			$equal = nxtchk($in, $n);
			$eq_b4 = 0;
		  }
		  elseif($tmpcounter == 0)
		  {
			$equal = nxtchk($in, $n);
			$tmpcounter = 1;
			if( ($i + 1) == $total )//to eliminate duplicate values at end
				$islast = 1;
		  }
		  elseif($equal == 0)//this is when number needs to be placed
		  {
			//$wait = 0;
			if( $eq_b4 == 0 )
			{
				$tempreg .= ', '.$p;
				$tmpcounter = $tmpcounter + 1;
			}
			elseif( $eq_b4 == 1 )//if only one adjacent value is equal then comma is placed
			{
				$tempreg .= ', '.$prev;
				$tmpcounter = $tmpcounter + 1;
				$eq_b4 = 0;
				if($tmpcounter == 3)
				{
					$printreg = $tempreg;
					$tmpcounter = 1;
					$tempreg = $p;
					$print = 1;
					goto printlabel;
				}
				else
				{
					$tempreg .= ', '.$p;
					$tmpcounter = $tmpcounter + 1;
					$eq_b4 = 0;
				}
			}
			elseif( $eq_b4 > 1 )
			{
				$tempreg .= ' - '.$prev;
				$tmpcounter = $tmpcounter + 1;
				if ($tmpcounter == 3)
				{
					$printreg = $tempreg;
					$tmpcounter = 1;
					$tempreg = $p;
					$print = 1;
					goto printlabel;
				}
				else
				{
					$tempreg .= ', '.$p;
					$tmpcounter = $tmpcounter + 1;
				}
				$eq_b4 = 0;//now it is set for comma
			}
			$equal = nxtchk($in, $n);
		  }
		  elseif($equal == 1)
		  {
			$eq_b4 = $eq_b4 + 1;//now it is set for hypen
			$equal = nxtchk($in, $n);
		  }
		}
		if( ($i + 1) == $total )//if the end is reached dot is placed at the end
		{
			if ($eq_b4 == -1 || $islast == 1)
				$tempreg = $p.'.';
			elseif ($eq_b4 == 0)
				$tempreg .= ', '.$p.'.';
			elseif ($eq_b4 == 1)
				$tempreg .= ' - '.$p.'.';
			elseif ($eq_b4 > 1)
				$tempreg .= ' - '.$p.'.';
			$printreg = $tempreg;
			$print = 1;
		}
		elseif($tmpcounter == 3)
		{
			//here send the $tempreg to pdf file
			$tempreg .= ',';
			$printreg = $tempreg;
			$print = 1;
			//above line is sent to pdf file
			$tmpcounter = 0;
			$tempreg = $n;
			if( ($i + 2) == $total )//to eliminate duplicate values at end
				$islast = 1;
			$eq_b4 = 0;
		}
		
		/*
		$curhal 	for current hall			$print_hal
		$printreg 	for regnos in current line
		$curdpt		for department name			$print_dpt
		$curtotal	for total dept wise			$print_dpt
		*/
		$prev = $p;
		printlabel:
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



?>