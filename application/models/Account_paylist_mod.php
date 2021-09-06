<?php
	class Account_paylist_mod extends CI_Model {
		
		public function __construct(){
		    // Call the CI_Model constructor
		    parent::__construct();
		}
		public function customiz_table($select,$table,$join,$where,$group,$order){
		    $this->db->select($select);
		    $this->db->from($table);

		    if(!empty($join)):
		       foreach ($join as $value) {
		          $this->db->join($value[0],$value[1]);
		       }
		    endif;

	      	if(!empty($where)):
	        	$this->db->where($where);
	      	endif;

		    if(!empty($group)):
		        $this->db->group_by($group);
		    endif;

		    if(!empty($order)):
		        foreach ($order as $order_val) {
		          $this->db->order_by($order_val[0],$order_val[1]);
		        }
		    endif;

		        // $this->db->limit('10');

		    $query = $this->db->get();
		    return $query->result_array();
	    }
		public function generate_payroll_list($ded_date){
			$ded_date = date('Y-m-d',strtotime($ded_date));
	        $payroll_list = array();
	        $select  = '*';
	        $table   = 'ebs.uni_personnel_access cu';
	        $join    = '';
	        $where   = 'emp_id = "'.$_SESSION['emp_id'].'"';
	        $group   = '';
	        $order   = '';

	        $personnel =  $this->Account_paylist_mod->customiz_table($select,$table,$join,$where,$group,$order);

	        // ===============================================================================================================================

	        $select  = '*';
	        $table   = 'ebs.cc_user cu';
	        $join    = array();
	        $join[]  = array('pis.employee3 e','e.emp_id = cu.cc_empid');
	        $where   = 'cc_type = "payroll"';
	        $group   = '';
	        $order   = '';

	        $payroll_user =  $this->Account_paylist_mod->customiz_table($select,$table,$join,$where,$group,$order);

	        // ===============================================================================================================================
	        ?> 
	        	<select class="form-control option" id="payroll"> 
	        <?php
		        foreach ($payroll_user as $p_value){

		            $select  = '*';
		            $table   = 'ebs.cc_group_access cu';
		            $join    = '';
		            $where   = 'cc_userid = "'.$p_value['cc_userid'].'"';
		            $group   = '';
		            $order   = '';

		            $access =  $this->Account_paylist_mod->customiz_table($select,$table,$join,$where,$group,$order);
		            foreach ($access as $ac_value){
                      	?>	
                      		<option value="<?php echo $p_value['cc_userid'] ?>" ><?php echo ucwords(strtolower($p_value['name'])) ?></option>
                      	<?php
                      	break;
                    }
		        }
	        ?>
		    </select>
          	<script type="text/javascript">
           		/*$('.option').on('change', function(event) {
                  	document.getElementById("go").disabled = false;
            	});*/
          	</script>
	        <?php
	    }
	}
?>