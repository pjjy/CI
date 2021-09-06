<?php
class Balance_entry_contrl extends CI_Controller {

        function __construct(){
			parent::__construct();
	        $this->load->model('Bal_entry_mod');
		}

		public function bal_mh_entry_contrl(){
			// $data['getHomePartners'] = $this->Accounting_mod->getHomePartners_mod();
			$this->load->view('balance_entry/Header');
			$this->load->view('balance_entry/pages/bal_entry_mh');
			$this->load->view('balance_entry/Footer');
			$this->load->view('balance_entry/pages/bal_entry-js');
		}

		public function search_mh_emp_contrl(){
			$post_emp_name = $_POST['post_emp_name'];
			$this->Bal_entry_mod->search_employeee_mod($post_emp_name);
		}

		public function insert_mh_emp_contrl(){
			$p_Employee_ID 		= $_POST['p_Employee_ID'];
			$p_Request_Number	= $_POST['p_Request_Number'];
			$p_Devt_Cost 		= $_POST['p_Devt_Cost'];
			$p_Bldg_Cost 		= $_POST['p_Bldg_Cost'];
			$p_Balance_Amount   	= $_POST['p_Balance_Amount'];
			$p_st1_Deduction	= $_POST['p_st1_Deduction'];
			$p_st2_Deduction 	= $_POST['p_st2_Deduction'];

			$this->Bal_entry_mod->insert_employeee_mh_mod($p_Employee_ID,$p_Request_Number,$p_Devt_Cost,$p_Bldg_Cost,$p_Balance_Amount,$p_st1_Deduction,$p_st2_Deduction);
		}

		public function bal_entry_hb_contrl(){
			$this->load->view('balance_entry/Header');
			$this->load->view('balance_entry/pages/bal_entry_hb');
			$this->load->view('balance_entry/Footer');
			$this->load->view('balance_entry/pages/bal_entry-js');
		}

		public function search_hb_emp_contrl(){
			$post_emp_name = $_POST['post_emp_name'];
			$this->Bal_entry_mod->search_employee_hb_mod($post_emp_name);
		}


		public function insert_hb_emp_contrl(){
			$p_emp_id = $_POST['p_emp_id'];
			$p_req_no = $_POST['p_req_no'];
			$p_bal_amt = $_POST['p_bal_amt'];
			$p_ded_amt = $_POST['p_ded_amt'];

			$this->Bal_entry_mod->insert_employeee_hb_mod($p_emp_id,$p_req_no,$p_bal_amt,$p_ded_amt);

		}
}



