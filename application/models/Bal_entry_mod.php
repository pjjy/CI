<?php
class Bal_entry_mod extends CI_Model {

    public function __construct(){
		// Call the CI_Model constructor
		parent::__construct();
	}


	public function search_employeee_mod($post_emp_name){

		$this->db->select('*');
		$this->db->from('pis.employee3 as emp');
		$this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_employee = emp.emp_id','inner');
		$this->db->like('emp.name', $post_emp_name);
		$this->db->limit(10);
		$query = $this->db->get();
        $emp_Data = $query->result_array();

        foreach($emp_Data as $value){   
			echo '
				<ul class="list-unstyled">
   					<li><a href="javascript:void(0);" id-req="'.$value['mh_serial_code'].'" id-name="'.$value['name'].'" id="'.$value['emp_id'].'" class="a_search_result">'.$value["name"].'</a>
   					</li>
				</ul>';				
		}


		echo '<script type="text/javascript">
				$(document).ready(function(){
					
					$(".a_search_result").on("click",function(){
						var emp_name = $(this).attr("id-name");
						var emp_id   = $(this).attr("id");
						var request_number = $(this).attr("id-req");

						$("#Employee_Name").val(emp_name);
						$("#Employee_ID").val(emp_id);
						$("#Request_Number").val(request_number);

						$(".search_result").hide();

					});

				});
			  </script>';
	}

	public function insert_employeee_mh_mod($p_Employee_ID,$p_Request_Number,$p_Devt_Cost,$p_Bldg_Cost,$p_Balance_Amount,$p_st1_Deduction,$p_st2_Deduction){

		// $data = array(
	 //        'mhhp_serial_code' => $p_Request_Number,
	 //        'emp_id' => $p_Employee_ID,
	 //        'mhhp_dvt_cost' => $p_Devt_Cost,
	 //        'mhhp_bldg_cost' => $p_Bldg_Cost,
	 //        'mhhp_strt_bal' => $p_Balance_Amount,
	 //        'mhhp_1st_cutoff' => $p_st1_Deduction,
	 //        'mhhp_2nd_cutoff' => $p_st2_Deduction,
	 //        'mhhp_current_bal' => $p_Balance_Amount,
	 //        'b_status' => 'active'
		// );
		// $this->db->insert('ebs.mhhp_balance', $data);


		$this->db->set('mhhp_serial_code', $p_Request_Number);
		$this->db->set('emp_id', $p_Employee_ID);
		$this->db->set('mhhp_dvt_cost', $p_Devt_Cost);
		$this->db->set('mhhp_bldg_cost', $p_Bldg_Cost);
		$this->db->set('mhhp_strt_bal', $p_Balance_Amount);
		$this->db->set('mhhp_1st_cutoff', $p_st1_Deduction);
		$this->db->set('mhhp_2nd_cutoff', $p_st2_Deduction);
		$this->db->set('mhhp_current_bal', $p_Balance_Amount);
		$this->db->set('b_status','active');
		$this->db->insert('ebs.mhhp_balance');
		$insert_id = $this->db->insert_id();
   		return  $insert_id;
	}


	public function search_employee_hb_mod($post_emp_name){

		$this->db->select('*');
		$this->db->from('pis.employee3 as emp');
		$this->db->join('ebs.mhhp_hbt_msfl as hb_msf', 'hb_msf.hbt_emp = emp.emp_id','inner');
		$this->db->like('emp.name', $post_emp_name);
		$this->db->limit(10);
		$query = $this->db->get();
        $emp_Data = $query->result_array();

        foreach($emp_Data as $value){   
			echo '
				<ul class="list-unstyled">
   					<li><a href="javascript:void(0);" id-req="'.$value['hbt_reqcode'].'" id-name="'.$value['name'].'" id="'.$value['emp_id'].'" class="a_search_result">'.$value["name"].'</a>
   					</li>
				</ul>';				
		}

		echo '<script type="text/javascript">
				$(document).ready(function(){
					
					$(".a_search_result").on("click",function(){
						var emp_name = $(this).attr("id-name");
						var emp_id   = $(this).attr("id");
						var request_number = $(this).attr("id-req");

						$("#Employee_Name_hb").val(emp_name);
						$("#Employee_ID_hb").val(emp_id);
						$("#Request_Number_hb").val(request_number);

						$(".search_hb_result").hide();

					});

				});
			  </script>';


	}

	public function insert_employeee_hb_mod($p_emp_id,$p_req_no,$p_bal_amt,$p_ded_amt){

		$data = array(
	        'hb_serial_code' => $p_req_no,
	        'emp_id' => $p_emp_id,
	        'hb_cutoff_amt' => $p_ded_amt,
	        'hb_balance' => $p_bal_amt,
	        'hb_status' => 'active'
		);
		$this->db->insert('ebs.mhhp_balance_hb', $data);
	}
}