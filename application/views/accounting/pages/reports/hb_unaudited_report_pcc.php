<?php
class unaudited_report_bypcc extends FPDF
{
    function Header()
    {
          $this->SetFont('Arial','',11);
          $this->Image(base_url().'assets/images/A.G.C..png',175,8,30,'PNG');
          $this->Cell(0,0,"ALTURAS GROUP OF COMPANIES",0,0,'L');      
          $this->Ln(5);
          $this->SetFont('Arial','B',9);
          $this->Cell(0,0,"COMPANY HOUSING PROGRAM",0,0,'L');      

          $this->SetFont('Arial','',9);
          $this->Ln(4);
          $this->Cell(0,0,"HABITAT - PRE AUDIT",0,0,'L');      
          $this->Ln(4);
          $this->SetFont('Arial','',9);
          $this->Cell(0,0,'Deduction Summary - '.$_GET['date'].'',0,0,'L');       
          $this->Ln(5);
    }

    function BasicTable($hb_report_unaudited_bypcc,$conso)
    { 
         
          $prev = 0.00;
          $sub_total = 0;
          $base_total = 0;
          $m_total = 0;

          if(!empty($conso)):
            $prev = $conso[0]['ldg_balance'];
          endif;
          $this->SetFont('Arial','',8);
            
               $m_total = $hb_report_unaudited_bypcc[3]+$prev;

               $this->Cell(16,6,$hb_report_unaudited_bypcc[0], 1,0,'C'); 
               $this->Cell(63,6,utf8_decode(strtoupper($hb_report_unaudited_bypcc[1])), 1,0,'L');  
               $this->Cell(63,6,$hb_report_unaudited_bypcc[2], 1,0,'L');
               $this->Cell(18,6,number_format($prev,2), 1,0,'R');
               $this->Cell(18,6,number_format($hb_report_unaudited_bypcc[3],2), 1,0,'R');
               $this->Cell(18,6,number_format($m_total,2) ,1,0,'R');
               $this->Ln(6);


               // $base_total+=$report_unaudited_all[3];
               // $sub_total+=$total;
               
    }

    public function total($prev_grandtotal,$grand_total,$base_total)
    {
     $this->Cell(142,6, 'GRAND TOTAL :',0,0,'R');  
     $this->Cell(18,6,number_format($prev_grandtotal,2), 0,0,'R');
     $this->Cell(18,6,number_format($base_total,2), 0,0,'R');
     $this->Cell(18,6,number_format($grand_total,2), 0,0,'R');

     $this->Ln(3);
     $this->Cell(142,6, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',0,0,'L'); 
     $this->Ln(9); 
 
     $this->SetFont('Arial','',9); 
     $this->Cell(0,6 ,'Extracted By: Mondragon, Ana Hilma Lobrigas - Accounting Clerk',0,0,'L');
     $this->Ln(4);
     $this->Cell(0,6 ,'Department:  Accounting Dept',0,0,'L');
     $this->Ln(4);
     $this->Cell(0,6 ,'Date: '.date('d F, Y'),0,0,'L');

    }
}

$pdf = new unaudited_report_bypcc();
// $header = array();

$pdf->SetTitle('Unaudited Report');
$pdf->SetFont('Arial','',14);
$pdf->AddPage("P","Letter");
$prev_grandtotal = 0;
$grand_total = 0;
$base_total  = 0;

foreach ($hb_report_unaudited_bypcc as  $value){
  $bu_array = array();

  $pcc_code = str_replace("CC","", $_GET['pcc_code']);
  $data = $this->Accounting_mod->hb_getdept_unaudited_bypcc_mod($value['bunit_code'],$_GET['date'],$pcc_code);

  $pdf->SetFont('Arial','B',9);
  if(!empty($data[0]['business_unit'])){
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


    if(!empty($bu_array)):
    if($pdf->getY() > 237):
    $pdf->AddPage("P","Letter");
    endif;
    $pdf->Cell(196,6, $data[0]['business_unit'],0,0,'L');  
    $pdf->Ln(6);    
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(16,6,'Salary #',1,0,'C'); 
    $pdf->Cell(63,6,'Name',1,0,'C');  
    $pdf->Cell(63,6,'Department',1,0,'C');
    $pdf->Cell(18,6,'Prev Bal',1,0,'C');
    $pdf->Cell(18,6,'Base',1,0,'C');
    $pdf->Cell(18,6,'Total',1,0,'C');
    $pdf->Ln(6);

      
      $sub_total = 0;
      $m_total = 0;
      $prev_bal = 0;
      foreach ($bu_array as $value2){

        $prev_date  = $this->Accounting_mod->prev_cutoff(date('d',strtotime($_GET['date']))); 
        $conso = $this->Accounting_mod->get_conso(date('Y-m',strtotime($_GET['date'])).'-'.$prev_date,$value2[4]);
        $pdf->BasicTable($value2,$conso);
        $sub_total  = $sub_total + $value2[3];
        if(!empty($conso[0]['ldg_balance'])):
          $m_total = $m_total + $value2[3] + $conso[0]['ldg_balance'];
          $prev_bal = $prev_bal + $conso[0]['ldg_balance'];
         

        else:
          $m_total = $m_total + $value2[3];
        endif;
      }


      $grand_total      = $grand_total + $m_total;
      $base_total       = $base_total + $sub_total;
      $prev_grandtotal = $prev_grandtotal + $prev_bal;

      $pdf->SetFont('Arial','B',9);
      $pdf->Cell(142,6, 'Sub-total  ',1,0,'R');  
      $pdf->Cell(18,6,number_format($prev_bal,2), 1,0,'R');
      $pdf->Cell(18,6,number_format($sub_total,2), 1,0,'R');
      $pdf->Cell(18,6,number_format($m_total,2), 1,0,'R');
      $pdf->Ln(9);

   $pdf->Ln(-1);
   $pdf->SetFont('Arial','B',9);
 
   $pdf->Cell(142,6, ' ',0,0,'L'); 
   $pdf->Ln(3); 
   endif;
  }
}
$pdf->total($prev_grandtotal,$grand_total,$base_total);
$pdf->Output();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="CompanyHousing-deduction_summary-'.date('ymd', strtotime($_GET['date'])).'.pdf');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
?>