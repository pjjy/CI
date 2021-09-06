<?php
    class unaudited_report_all extends FPDF{   
        function Footer(){
            $this->SetY(-15);
            $this->SetFont("Arial", "", 7);
            $this->Cell(0, 10, "Page ".$this->PageNo()." - {nb}", 0, 0, "C");
            $this->SetY(-10);
            $this->Cell(0,6 ,'Run Date/Time: '.date('Y-m-d').'/'.date('H:i:s'),0,0,'L');
        }
        function Details($date,$bu_name){
            // $this->Cell(5,5,'','',0,'C','');
            $this->SetFont('Arial','B',9);
            $this->Cell(190,5,"ALTURAS GROUP OF COMPANIES",0,0,'L');
            $this->Image(base_url().'assets/images/A.G.C..png',175,8,30,'PNG');
            // $this->Cell(5,5,'','',0,'C','');
            $this->Ln();
            // $this->Cell(5,5,'','',0,'C','');
            $this->Cell(190,5,"EMPLOYEE BENEFITS MODULES - MARCELA HOMES",0,0,'L');
            $this->Ln();
            // $this->Cell(5,5,'','',0,'C','');
            $this->Cell(190,5,strtoupper($bu_name),0,0,'L');
            $this->Ln();
            // $this->Cell(5,5,'','',0,'C','');
            $this->Cell(190,5,"DEDUCTION SUMMARY FOR ".strtoupper(date('F d,Y',strtotime($date))),0,0,'L');
            $this->Ln();

            $this->SetFont('Arial','',9);
        }
        function SectionHeaders($section){
            $this->Ln();
            $this->SetFont('Arial','B',8);
            // $this->Cell(5,6,'','',0,'C','');
            $this->Cell(180,7,'Section: '.strtoupper($section),'',0,'L');
            $this->Ln();
        }
        function tableHeaders(){
            $this->SetFont('Arial','B',8);
            $this->setFillColor(230,230,230);
            // $this->Cell(5,6,'','',0,'C','');
            $this->Cell(10,6,'No','LTB',0,'C','true');
            $this->Cell(25,6,'Payroll No','LTB',0,'C','true');
            $this->Cell(25,6,'Serial','LTB',0,'C','true');
            $this->Cell(65,6,'  Name','LTB',0,'L','true');
            $this->Cell(25,6,'Prev. Bal.','LTB',0,'C','true');
            $this->Cell(25,6,'Base','LTBR',0,'C','true');
            $this->Cell(25,6,'Total','LTBR',0,'C','true');
            $this->Ln();
        }
        function table_data($no,$payroll,$serial,$name,$prev,$deduction,$total){

            $this->SetFont('Arial','',8);
            $this->Cell(10,6,$no,'LTB',0,'C','');
            $this->Cell(25,6,$payroll,'LTB',0,'C','');
            $this->Cell(25,6,$serial,'LTB',0,'C','');
            $this->Cell(65,6,utf8_decode($name),'LTB',0,'L','');
            $this->Cell(25,6,number_format($prev,2),'LTB',0,'C','');
            $this->Cell(25,6,number_format($deduction,2),'LTB',0,'C','');
            $this->Cell(25,6,number_format($total,2),'LTBR',0,'C','');
            $this->Ln();
        }
        function signature($name,$position){
            // $this->Ln(10);
            // $this->Cell(148,4,'','',0,'C','');
            // $this->Cell(25,4,'GRAND TOTAL','B',0,'R','');
            // $this->Cell(21,4,number_format($grand_total,2),'B',0,'R','');
            // $this->Ln(4);
            // $this->Cell(148,1,'','',0,'C','');
            // $this->Cell(50,1,'','T',0,'L','');
            // $this->Ln(0);
            // $this->Cell(148,1,'','',0,'C','');
            // $this->Cell(50,1,'','T',0,'L','');
            $this->Ln(30);
            $this->SetFont('Arial','',9);
            $this->Cell(180,5,'Prepared by: '.ucwords(strtolower($name)),'',0,'L','');
            $this->Ln();
            $this->Cell(180,5,'Department : '.ucwords(strtolower($position)),'',0,'L','');
            $this->Ln();
            $this->Cell(180,5,'Date: '.date('F d,Y'),'',0,'L','');
        }
        function grand_tot($grand_total){
            $this->Ln();
            $this->Cell(148,4,'','',0,'C','');
            $this->Cell(25,4,'GRAND TOTAL','B',0,'R','');
            $this->Cell(21,4,number_format($grand_total,2),'B',0,'R','');
            $this->Ln(4);
            $this->Cell(148,1,'','',0,'C','');
            $this->Cell(50,1,'','T',0,'L','');
            $this->Ln(0);
            $this->Cell(148,1,'','',0,'C','');
            $this->Cell(50,1,'','T',0,'L','');
        }
        // function Header()
        // {
        //       $this->SetFont('Arial','B',8);
        //       $this->Image(base_url().'assets/images/A.G.C..png',175,8,30,'PNG');
        //       $this->Cell(0,0,"ALTURAS GROUP OF COMPANIES",0,0,'L');      
        //       $this->Ln(5);
        //       $this->Cell(0,0,"COMPANY HOUSING PROGRAM",0,0,'L');      
        //       $this->Ln(4);
        //       $this->Cell(0,0,"MARCELA HOMES",0,0,'L');      
        //       $this->Ln(4);
        //       $this->Cell(0,0,'DEDUCTION SUMMARY FOR',0,0,'L');       
        //       // $this->Cell(0,0,'Deduction Summary - '.$_GET['date'].'',0,0,'L');       
        //       $this->Ln(5);
        // }

        // function BasicTable($report_unaudited_all,$conso){ 
             
        //       $prev       = 0.00;
        //       $sub_total  = 0;
        //       $base_total = 0;
        //       $m_total    = 0;

        //       if(!empty($conso)):
        //         $prev = $conso[0]['ldg_balance'];
        //       endif;
        //       $this->SetFont('Arial','',8);

        //      $m_total = $report_unaudited_all[3]+$prev;

        //      $this->Cell(16,6,$report_unaudited_all[0], 1,0,'C'); 
        //      $this->Cell(63,6,utf8_decode(strtoupper($report_unaudited_all[1])), 1,0,'L');  
        //      $this->Cell(63,6,$report_unaudited_all[2], 1,0,'L');
        //      $this->Cell(18,6,number_format($prev,2), 1,0,'R');
        //      $this->Cell(18,6,number_format($report_unaudited_all[3],2), 1,0,'R');
        //      $this->Cell(18,6,number_format($m_total,2) ,1,0,'R');
        //      $this->Ln(6);
        // }

        // public function total($prev_grandtotal,$grand_total,$base_total,$extract_name, $extrat_position){
        //      $this->Cell(142,6, 'GRAND TOTAL :',0,0,'R');  
        //      $this->Cell(18,6,number_format($prev_grandtotal,2), 0,0,'R');
        //      $this->Cell(18,6,number_format($base_total,2), 0,0,'R');
        //      $this->Cell(18,6,number_format($grand_total,2), 0,0,'R');

        //      $this->Ln(3);
        //      $this->Cell(142,6, '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',0,0,'L'); 
        //      $this->Ln(9); 
         
        //      $this->SetFont('Arial','',9); 
        //      $this->Cell(0,6 ,'Extracted By: '.$extract_name.' - '.$extrat_position.' ',0,0,'L');
        //      $this->Ln(4);
        //      $this->Cell(0,6 ,'Department: Accounting Dept',0,0,'L');
        //      $this->Ln(4);
        //      $this->Cell(0,6 ,'Date: '.date('d F, Y'),0,0,'L');
        //      // "'.$emp.'"
        // }
    }

    $pdf = new unaudited_report_all();

    $pdf->SetTitle('Unaudited Report');
    $pdf->SetFont('Arial','',14);
    // $pdf->AddPage("P","Letter");
    $prev_grandtotal = 0;
    $grand_total = 0;
    $base_total  = 0;
    $select  = '*';
    $table   = 'ebs.cc_group_access cu';
    $join    = '';
    $where   = 'cc_userid = "'.$payroll.'"';
    $group   = '';
    $order   = '';
    $access  =  $this->Accounting_mod->customiz_table($select,$table,$join,$where,$group,$order);
    foreach ($access as $ac_value){
        $select  = '*';
        $table   = 'ebs.cc_pcc cu';
        $join    = '';
        $where   = 'cc_id = "'.$ac_value['cc_id'].'"';
        $group   = 'pcc_code';
        $order   = array();
        $order[] = array('pcc_code','asc');

        $pcc =  $this->Accounting_mod->customiz_table($select,$table,$join,$where,$group,$order);
        
        $no_copy_h = 1;
        $grndtotal = 0;
        $paymentmode = 'DEDUCTION';
        $status = 'AMORTIZED';

        foreach ($pcc as $pcc_value){

            $select  = '*';
            $table   = 'ebs.mhhp_balance bal';
            $join    = array();
            $join[]  = array('ebs.mhhp_request_msfl msf', 'msf.mh_serial_code = bal.mhhp_serial_code');
            $join[]  = array('pis.employee3 emp', 'emp.emp_id = msf.mh_employee AND emp.pcc = '.$pcc_value['pcc_code'].'');
            $where   = "bal.mhhp_current_bal > 0 ";
            $where   .= "and msf.mh_paymentmode = '".$paymentmode."' ";
            $where   .= "and msf.mh_status_REQ = '".$status."' ";
            $group   = '';
            $order   = '';

            $get_summary =  $this->Accounting_mod->customiz_table($select,$table,$join,$where,$group,$order);
            // var_dump($get_summary);
            $no = 1;
            if(!empty($get_summary)){
                if($no_copy_h === 1){
                    $select  = '*';
                    $table   = 'ebs.cc_group cu';
                    $join    = '';
                    $where   = 'cc_id = "'.$ac_value['cc_id'].'"';
                    $group   = '';
                    $order   = '';

                    $cc_group =  $this->Accounting_mod->customiz_table($select,$table,$join,$where,$group,$order);

                    $pdf->AddPage("P","Letter");
                    $pdf->AliasNbPages();
                    $pdf->Details($date,$cc_group[0]['cc_group_name']);
                    $no_copy_h++;
                }
               
                $pdf->SectionHeaders($pcc_value['pcc_name']);
                $pdf->tableHeaders();
                foreach ($get_summary as $value){

                    $amortization           = $this->Accounting_mod->get_amort($value['mh_serial_code']);
                    $split_date             = $this->Accounting_mod->split_predate($amortization[0]['amrt_effectivedate'], $date);
                    $selected_DATE          = date('Y-m-d', strtotime($date));
                    $partial_deduction      = $this->Accounting_mod->partial_deduction($value['mh_serial_code']);
                    $autoSPLIT              = $this->Accounting_mod->auto_splitdeduction($value['mh_serial_code']);

                    
                    if(date('d', strtotime($selected_DATE)) === '15' || date('d', strtotime($selected_DATE)) === '05'){
                        if($value['mhhp_current_bal'] < $value['mhhp_1st_cutoff']){

                            $deduction = $value['mhhp_current_bal'];
                        }else{

                            $deduction = $value['mhhp_1st_cutoff'];
                        }
                    }elseif (date('d', strtotime($selected_DATE)) === '30' || date('d', strtotime($selected_DATE)) === '20' || date('d', strtotime($selected_DATE)) === '29' || date('d', strtotime($selected_DATE)) === '28') {

                        $balance            = $this->Accounting_mod->mhhp_balance2ndcutoff($value['mh_serial_code']);
                        $deduction          = $balance[0]['mhhp_2nd_cutoff'];
                    }

                    $deduction_date = null;     

                    if(!empty($partial_deduction)){
                        $amount             = $partial_deduction['prt_amount'];
                        $date_amrt          = $partial_deduction['prt_effectivedate'];
                        $date_select        = $date;
                        $deduction_date     = $this->Accounting_mod->prev_deductiondate($date_amrt, $date_select);

                    }elseif (!empty($amortization)) {
                        
                        $date_amrt                      = $amortization[0]['amrt_effectivedate'];
                        $scheduled_date                 = $this->Accounting_mod->prev_deductiondate($date_amrt, $date);
                        $dropoff_amortization_pre       = $this->Accounting_mod->dropoff_amortization($value['mh_serial_code'], $date);

                        if(!empty($autoSPLIT) && ($split_date['ded_date'] == $selected_DATE) && ($dropoff_amortization_pre == null)){

                            $deduction_date = date('Y/m/d', strtotime($split_date['ded_date']));

                        }elseif($scheduled_date == null && ($dropoff_amortization_pre != null)){

                            $deduction_date = date('Y/m/d', strtotime($dropoff_amortization_pre['DPDed_date']));
                        }else{

                            $deduction_date = $scheduled_date;
                        }

                    }
                    // var_dump($deduction_date);
                    
                    $newdate    = date('Y-m',strtotime("-1 months", strtotime($date)));
                    $prev_date  = $this->Accounting_mod->prev_cutoff(date('d',strtotime($date))); 
                    $conso      = $this->Accounting_mod->get_conso($newdate.'-'.$prev_date,$value['emp_id']);
                    $prev       = 0.00;
                    $m_total    = 0.00;

                    if(!empty($conso)){

                        $prev = $conso[0]['ldg_balance'];
                    }else{

                        $prev = 0;
                    }

                    $m_total        = $deduction+$prev;
                    $audit          = $this->Accounting_mod->audited_list($value['mh_serial_code'], date('Y-m-d', strtotime($deduction_date)));

                    if(($deduction_date != null) && (empty($audit))){
                        $grndtotal = $grndtotal+$m_total;
                        
                        if($m_total != 0){
                            $pdf->table_data($no,$value['payroll_no'],$value['mhhp_serial_code'],$value['name'],$prev,$deduction,$m_total);
                            $no++;
                        }
                    }
                }
            }
        }
        $select  = '*';
        $table   = 'pis.employee3 e';
        $join    = '';
        $where   = 'e.emp_id = "'.$_SESSION['emp_id'].'"';
        $group   = '';
        $order   = '';
        $incharge =  $this->Accounting_mod->customiz_table($select,$table,$join,$where,$group,$order);
        
        if($pdf->GetY() >=225){
            $pdf->AddPage("P","Letter");
            // $pdf->AliasNbPages();
        }
        if($no_copy_h > 1){
          // $pdf->signature(utf8_decode($incharge[0]['name']),$incharge[0]['position'],$grndtotal);
          $pdf->grand_tot($grndtotal);
          $pdf->signature(utf8_decode($incharge[0]['name']),$incharge[0]['position']);
        }
    }
    // $emp = $this->Accounting_mod->get_extracted($_SESSION['emp_id']);
    // $pdf->total($prev_grandtotal,$grand_total,$base_total,$emp[0]['name'],$emp[0]['position']);
    $pdf->Output();

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="CompanyHousing-deduction_summary-'.date('ymd', strtotime($date)).'.pdf');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');  
?>
