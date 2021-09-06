<?php
class Accounting_contrl extends CI_Controller {

        public function __construct(){
			parent::__construct();
	        	$this->load->model('Accounting_mod');
	        	$this->load->model('Account_paylist_mod');
		}
		
		public function home(){
			$data['getHomePartners'] = $this->Accounting_mod->getHomePartners_mod();

			$this->load->view('accounting/Header');
			$this->load->view('accounting/pages/home',$data);
			$this->load->view('accounting/Footer');
			$this->load->view('accounting/pages/home-js');
		}

		public function modal_employeee_monitoring_contrl(){
			$post_id = $_POST['post_id'];
			$this->Accounting_mod->modal_employeee_monitoring_mod($post_id);
		}

		public function modal_unaudited_contrl(){
			$this->Accounting_mod->modal_unaudited_mod_v1();
			// $this->Accounting_mod->summary_modal();
		}

		public function modal_unaudited_habitat_contrl(){
			$this->Accounting_mod->modal_unaudited_habitat_mod();
		}

		public function report_unaudited_all_contrl($date){
			$this->load->library('Pdf'); 
			// $date 						 	= $_GET['date'];
			$data['report_unaudited_all'] 	= $this->Accounting_mod->getunaudited_all_mod($date);


			$this->load->view('accounting/pages/reports/unaudited_report_all',$data);
		}

		// public function deduction_summary(){
		public function deduction_summary($date,$sum_type,$payroll){
			$this->load->library('Pdf'); 
		   	$data['date']     	= $date;
		    	$data['sum_type']     	= $sum_type;
		    	$data['payroll']    	= $payroll;
		    	
			$this->load->view('accounting/pages/reports/unaudited_report_all',$data);
		}

		public function report_unaudited_bypcc_contrl(){
			$this->load->library('Pdf'); 
			$pcc_code = $_GET['pcc_code'];
			$data['report_unaudited_bypcc'] = $this->Accounting_mod->getunaudited_bypcc_mod($pcc_code);

			
			$this->load->view('accounting/pages/reports/unaudited_report_bypcc',$data);
		}

		public function report_slip_all_contrl(){
			$this->load->library('Pdf'); 
			$date = $_GET['date'];
			$data['report_unaudited_all'] = $this->Accounting_mod->getunaudited_all_mod($date);
			$this->load->view('accounting/pages/reports/deduction_slip_all',$data);
		}

		public function report_slip_bypcc_contrl(){
			$this->load->library('Pdf'); 
			$pcc_code  = $_GET['pcc_code'];
			$data['report_slip_bypcc'] = $this->Accounting_mod->getunaudited_bypcc_mod($pcc_code);
			$this->load->view('accounting/pages/reports/deduction_slip_bypcc',$data);
		}

		public function hb_report_all_contrl(){
			$this->load->library('Pdf'); 
			$data['hb_report_unaudited_all'] = $this->Accounting_mod->hb_getunaudited_all_mod();
			$this->load->view('accounting/pages/reports/hb_unaudited_report_all',$data);
		}

		public function hb_report_bypcc_contrl(){
			$this->load->library('Pdf'); 
			$pcc_code = $_GET['pcc_code'];
			$data['hb_report_unaudited_bypcc'] = $this->Accounting_mod->hb_getunaudited_all_mod();
			$this->load->view('accounting/pages/reports/hb_unaudited_report_pcc',$data);
		}


		public function hb_slip_all_contrl(){
			$this->load->library('Pdf');
			$data['hb_report_unaudited_all'] = $this->Accounting_mod->hb_getunaudited_all_mod();
			$this->load->view('accounting/pages/reports/hb_slip_all',$data);
		}

		public function hb_slip_bypcc_contrl(){
			$this->load->library('Pdf');
			$data['hb_report_unaudited_all'] = $this->Accounting_mod->hb_getunaudited_all_mod();
			$this->load->view('accounting/pages/reports/hb_slip_bypcc',$data);
		}


		public function habitat_contrl(){
			$data['getHomePartners'] = $this->Accounting_mod->hb_getHomePartners_mod();
			$this->load->view('accounting/Header');
			$this->load->view('accounting/pages/habitat',$data);
			$this->load->view('accounting/Footer');
			$this->load->view('accounting/pages/home-js');
		}

		public function hb_modal_employeee_monitoring_contrl(){
			$post_id = $_POST['post_id'];
			$this->Accounting_mod->hb_modal_employeee_monitoring_mod($post_id);
		}


		public function mh_walkinpay_contrl(){
			$post_id = $_POST['post_id'];
			$this->Accounting_mod->mh_walkinpay_mod($post_id);
		}

		public function mh_sub_walkinpay_contrl(){
			$p_wlkin_acc_no = $_POST['p_wlkin_acc_no'];
			$p_date_v 	= $_POST['p_date_v'];
			$p_payamt_v 	= $_POST['p_payamt_v'];
			$p_ornumber_v 	= $_POST['p_ornumber_v'];

			$this->Accounting_mod->sub_walkinpay_mod($p_wlkin_acc_no,$p_date_v,$p_payamt_v,$p_ornumber_v);

		}

		public function hb_walkinpay_contrl(){
			$post_id = $_POST['post_id'];
			$this->Accounting_mod->hb_walkinpay_mod($post_id);
		}

		public function hb_sub_walkinpay_contrl(){
			$p_p_wlkin_acc_no	 = $_POST['p_p_wlkin_acc_no'];	
			$p_hb_wlk_datepicker = $_POST['p_hb_wlk_datepicker'];		
			$p_hb_wlkpay_amt	 = $_POST['p_hb_wlkpay_amt'];
			$p_hb_wlk_orno		 = $_POST['p_hb_wlk_orno'];		
		    $this->Accounting_mod->hb_sub_walkinpay_mod($p_p_wlkin_acc_no,$p_hb_wlk_datepicker,$p_hb_wlkpay_amt,$p_hb_wlk_orno);
		}
		public function mh_chart_contrl(){
			

				 $this->Accounting_mod->mh_chart_mod();

		}
		public function payroll_list(){

		    $ded_date     = $_POST['date'];
		    $this->Account_paylist_mod->generate_payroll_list($ded_date);
		}

}