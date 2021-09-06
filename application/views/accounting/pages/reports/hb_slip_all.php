<?php

class CA_PDF extends FPDF
{
	var $B;
	var $I;
	var $U;
	var $HREF;



	function ca_Slip_form($rec,$no,$data,$date)
	{

		// $this->SetDash(2.5,2.5); //5mm on, 5mm off
		// $this->Line(0,135,300,135);
		// $this->SetDash(0,0); //5mm on, 5mm off

		$this->SetTextColor(28, 28, 28);

		// $this->Rect($rec[0], $rec[1], $rec[2], $rec[3] , 'd');
		$this->SetFont("Arial", "B", 9);

		$this->SetXY($rec[0], $rec[1]+2);
		$this->Cell(2.5, 2.5,'', 0, 0, "L");
		$this->Cell(100, 2.5,'ALTURAS GROUP OF COMPANIES', 0, 0, "C");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+6);
		$this->SetFont("Arial", "", 9);
		$this->Cell(2.5, 2.5,'', 0, 0, "L");
		$this->Cell(100, 2.5,'Company Housing Program', 0, 0, "C");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+12);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(15.5, 5,'Status :', 0, 0, "L");
		$this->Cell(30.5, 5,'PRE-AUDIT', 'B', 0, "L");
		$this->Cell(7.5, 5,'', 0, 0, "L");
		$this->Cell(10.5, 5,'Date :', 0, 0, "L");
		$this->Cell(25.5, 5,date('F d,Y',strtotime($date)), 'B', 0, "L");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+18);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(18.5, 5,'Employee :', 0, 0, "L");
		$this->Cell(71, 5,$data['name'], 'B', 0, "L");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+24);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(18.5, 5,'Salary # :', 0, 0, "L");
		$this->Cell(71, 5,$data['payroll_no'], 'B', 0, "L");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+30);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(18.5, 5,'Department :', 0, 0, "L");
		$this->Cell(71, 5,$data['dept_name'], 'B', 0, "L");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+38);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(25.5, 5,'DEDUCTION', 'LTB', 0, "C");
		$this->Cell(25.5, 5,'FORWARD ', 'LTB', 0, "C");
		$this->Cell(20.5, 5,'BASE', 'LTBR', 0, "C");
		$this->Cell(20.5, 5,'TOTAL', 'LTBR', 0, "C");
		$this->ln();
		$this->SetFont("Arial", "B", 7);
		$this->SetXY($rec[0], $rec[1]+43);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(25.5, 5,'MARCELA HOMES', 'LB', 0, "C");
		$this->Cell(25.5, 5,'0.00', 'LB', 0, "C");
		$this->Cell(20.5, 5,number_format($data['deduction'],2), 'LBR', 0, "C");
		$this->Cell(20.5, 5,number_format($data['deduction'],2), 'LBR', 0, "C");
		$this->ln();
		$this->SetFont("Arial", "B", 7);
		$this->SetXY($rec[0], $rec[1]+48);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(25.5, 5,'TOTAL AMOUNT P', 'LB', 0, "C");
		$this->Cell(25.5, 5,'0.00', 'LB', 0, "C");
		$this->Cell(20.5, 5,number_format($data['deduction'],2), 'LBR', 0, "C");
		$this->Cell(20.5, 5,number_format($data['deduction'],2), 'LBR', 0, "C");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+56);
		$this->Cell(2.5, 5,'', 0, 0, "L");
		$this->Cell(25.5, 5,'PREPARED BY :        Mondragon, Ana Hilma Lobrigas', '', 0, "L");
		$this->Cell(65.5, 5,'', 'B', 0, "C");
		$this->ln();
		$this->SetXY($rec[0], $rec[1]+63);
		$this->Cell(93.5, 5,'- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - - - - - - - - - ', 0, 0, "L");
		$this->SetXY($rec[0], $rec[1]+68);

	}


	//

	//code to add HTML
	function WriteHTML($html)
	{
	    // HTML parser
	    $html = str_replace("\n",' ',$html);
	    $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	    foreach($a as $i=>$e)
	    {
	        if($i%2==0)
	        {
	            // Text
	            if($this->HREF)
	                $this->PutLink($this->HREF,$e);
	            else
	                $this->Write(5,$e);
	        }
	        else
	        {
	            // Tag
	            if($e[0]=='/')
	                $this->CloseTag(strtoupper(substr($e,1)));
	            else
	            {
	                // Extract attributes
	                $a2 = explode(' ',$e);
	                $tag = strtoupper(array_shift($a2));
	                $attr = array();
	                foreach($a2 as $v)
	                {
	                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
	                        $attr[strtoupper($a3[1])] = $a3[2];
	                }
	                $this->OpenTag($tag,$attr);
	            }
	        }
	    }
	}

	function OpenTag($tag, $attr)
	{
	    // Opening tag
	    if($tag=='B' || $tag=='I' || $tag=='U')
	        $this->SetStyle($tag,true);
	    if($tag=='A')
	        $this->HREF = $attr['HREF'];
	    if($tag=='BR')
	        $this->Ln(5);
	}

	function CloseTag($tag)
	{
	    // Closing tag
	    if($tag=='B' || $tag=='I' || $tag=='U')
	        $this->SetStyle($tag,false);
	    if($tag=='A')
	        $this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
	    // Modify style and select corresponding font
	    $this->$tag += ($enable ? 1 : -1);
	    $style = '';
	    foreach(array('B', 'I', 'U') as $s)
	    {
	        if($this->$s>0)
	            $style .= $s;
	    }
	    $this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
	    // Put a hyperlink
	    $this->SetTextColor(0,0,255);
	    $this->SetStyle('U',true);
	    $this->Write(5,$txt,$URL);
	    $this->SetStyle('U',false);
	    $this->SetTextColor(0);
	}


}

$pdf = new CA_PDF();
$pdf->AliasNbPages();
$no = 0;
$height = 8;
$pdf->SetTitle('Deduction Slip All');
$pdf->AddPage("P","Letter");
$pdf->SetAutoPageBreak(0,0);
foreach ($hb_report_unaudited_all as  $value){
  $bu_array = array();
  $data = $this->Accounting_mod->hb_getdept_unaudited_all_mod($value['bunit_code'],$_GET['date']);
    // public function getdept_unaudited_bypcc_mod($locbu,$date,$pcc_code){

  $pdf->SetFont('Arial','B',9);
  if(!empty($data[0]['business_unit'])):
    foreach ($data as $value2){	

    $cutoff = $this->Accounting_mod->get_cutoff($value['company_code'],$value['bunit_code'],$_GET['date']);
    if(in_array(date('d',strtotime($_GET['date'])), $cutoff)):

      if($cutoff[0] == '05' || $cutoff[0] == '15'):
        $deduction = $value2['hb_cutoff_amt'];
      elseif($cutoff[0] == '20' || $cutoff[0] == '30'):
        $deduction = $value2['hb_cutoff_amt'];
      endif;
      if(!empty($deduction) && $deduction > 0):

      	 if(empty($value2['dept_code'])){
           $data1 = $this->Accounting_mod->locb($value['company_code'],$value['bunit_code']);
           $r=$data[0]['business_unit'];
        }
        else{
           $data2 = $this->Accounting_mod->locc($value['company_code'],$value['bunit_code'],$value['dept_code']);
           $r= $data2[0]['dept_name'];
        }

        $array = array();
        $array[] = $value2['payroll_no'];
        $array[] = $value2['name'];
        $array[] = $r;
        $array[] = $deduction;
        $array[] = $value2['emp_id'];
        $bu_array[] = $array;
      endif;
    endif;
    }

// $pdf->SetMargins(10, 30 , 0);
	
	foreach ($bu_array as $bu_val):

		if($no % 2):
			$rectangle = array('112', $height, '95', '62');
			$height = $height + 66;
		else:
			$rectangle = array('5', $height, '95', '62');
		endif;

		$data  = array(
						'payroll_no' 			=>	$bu_val[0],
						'name' 					=>	$bu_val[1],
						'dept_name' 			=>	$bu_val[2],
						'deduction' 			=>	$bu_val[3],
						'emp_id' 				=>	$bu_val[4]
						);
		$pdf->ca_Slip_form($rectangle,$no,$data,$_GET['date']);
		$no++;

		if($no % 8 == 0):
		$pdf->AddPage("P","Letter");
		$height = 8;
		$no = 0;
		endif;

	endforeach;

  endif;
}
$pdf->Output();



?>