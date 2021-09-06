<?php
class Accounting_mod extends CI_Model {

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
    public function getHomePartners_mod(){
    		$this->db->select('*');
				$this->db->from('ebs.mhhp_amortizationsched as amrt');
				$this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_serial_code = amrt.mhhp_serial_code','inner');
				// $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = amrt.mhhp_serial_code','inner');
				$this->db->join('ebs.mhhp_balance as bal', 'bal.mhhp_serial_code = amrt.mhhp_serial_code','inner');
				$this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.mh_employee','inner');
				$query = $this->db->get();
        return $query->result_array();
    }

 
    public function modal_employeee_monitoring_mod($post_id){

    	$this->db->select('*');
		$this->db->from('ebs.mhhp_amortizationsched as amrt');
		$this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_serial_code = amrt.mhhp_serial_code','inner');
		// $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = amrt.mhhp_serial_code','inner');
		$this->db->join('ebs.mhhp_balance as bal', 'bal.mhhp_serial_code = amrt.mhhp_serial_code','inner');
		$this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.mh_employee','inner');
		$this->db->where('msf.mh_serial_code', $post_id);
		$query = $this->db->get();
        $emp_Data = $query->result_array();

   		echo '
		<div class="modal-dialog modal-lg" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">'.$emp_Data[0]['name'].'-'.$emp_Data[0]['mhhp_serial_code'].'</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">';
				  echo '<div class="table-responsive">
                <table class="table table-bordered tble-data" id="" width="100%" cellspacing="0">
                  <thead>
                  <tr style="font-size:11.5px">
                    <th>DEDUCTION DATE</th>
                    <th style="text-align: right;">FIXED TOTAL</th>
                    <th style="text-align: right;">DED CODE</th>
                    <th style="text-align: right;">AMOUNT BALANCE</th>
                   </tr>
                  </thead>
                <tbody>';

		            $this->db->select('*');
					$this->db->from('ebs.mhhp_deduction as mhded');
					$this->db->where('mhded.mhhp_serial_code', $post_id);
					// $this->db->order_by('mhded.hdT_cutoff', 'desc');
					$query2 = $this->db->get();
			        $ded_Data = $query2->result_array();
			        if(!empty($ded_Data)){
		               	foreach($ded_Data as $value){    
		                  echo '<tr style="font-size:13px">';
		                    echo '<td>'.$value['ded_deductiondate'].'</td>';
		                    echo '<td style="text-align: right;">'.number_format($value['ded_amount'],2).'</td>';
		                    echo '<td style="text-align: right;">'.$value['ded_deductioncode'].'</td>';
		                    echo '<td style="text-align: right;">'.number_format($value['ded_payable'],2).'</td>';
		                  echo '</tr>';
		                }


		            }
					echo '</tbody>';
					echo '<thead>
						<tr style="font-size:11.5px">
						<th>DEDUCTION DATE</th>
						<th style="text-align: right;">FIXED TOTAL</th>
						<th style="text-align: right;">DED CODE</th>
						<th style="text-align: right;">AMOUNT BALANCE</th>
						</tr>
						</thead>';

					echo '<tbody>';

		            
			        if(!empty($emp_Data))
			        {
			        	if(!empty($ded_Data))
			        	{
			        		$get_deddate = $this->getEmpLastDedDate($ded_Data[0]['ded_emp']);
			        	
				        	if(!empty($ded_Data[0]['ded_datededucted']))
				        	{
				        		$date_sched = date('Y-m-d', strtotime($get_deddate->ded_deductiondate));
				        	}
				        	else
				        	{
				        		$date_sched = date('Y-m-d', strtotime('Y-m-30'));
				        	}

				        	if($emp_Data[0]['mhhp_1st_cutoff']== '0.00')
				        	{
				        		$divideAmount = $emp_Data[0]['mhhp_1st_cutoff'] + $emp_Data[0]['mhhp_2nd_cutoff'];
				        	}
				        	else
				        	{
				        		$divideAmount = $emp_Data[0]['mhhp_2nd_cutoff'];
				        	}
				       		
				        	$div = ceil($emp_Data[0]['mhhp_current_bal'] / $divideAmount);
				        	$ded_dates = $this->getDeductionDates($date_sched, $div);
				        	$balance = floatval($emp_Data[0]['mhhp_current_bal']);
				        	$debit = floatval($divideAmount);
				        	$pamount = 0;

			               	for($x = 0 ;$x < $div;$x ++)
			               	{ 
				               	($ded_dates[$x] == $get_deddate->ded_deductiondate) ? @$date = $ded_dates[$x].'(CLOSED)': @$date = $ded_dates[$x];
				               	
				               	if($debit < $balance)
				               	{

					               	$pamount = ($balance - $debit);
					               	echo '<tr style="font-size:13px">';
					                echo '<td>'.@$date.'</td>';
					                echo '<td style="text-align: right;">'.number_format($divideAmount,2).'</td>';
					                echo '<td style="text-align: right;">-----</td>';
					                echo '<td style="text-align: right;">'.number_format(abs($pamount),2).'</td>';
					                echo '</tr>';
					                $balance = $pamount;
					            }
					            else
					            {
					               	$debit = $balance;
					               	$pamount = ($balance - $debit);
					               	echo '<tr style="font-size:13px">';
					                echo '<td>'.@$date.'</td>';
					                echo '<td style="text-align: right;">'.number_format($debit, 2).'</td>';
					                echo '<td style="text-align: right;">-----</td>';
					                echo '<td style="text-align: right;">'.number_format(abs($pamount),2).'</td>';
					                echo '</tr>';
					                $balance = 0.00;
					            }   
				            }   
			        	}
			        	else
			        	{
			        		if($emp_Data[0]['mhhp_1st_cutoff']== '0.00')
				        	{
				        		$divideAmount = $emp_Data[0]['mhhp_1st_cutoff'] + $emp_Data[0]['mhhp_2nd_cutoff'];
				        	}
				        	else
				        	{
				        		$divideAmount = $emp_Data[0]['mhhp_2nd_cutoff'];
				        	}

			        		$div = ceil($emp_Data[0]['mhhp_strt_bal'] / $divideAmount);
			        		$start_bal = $emp_Data[0]['mhhp_strt_bal'];
			        		$ded_amt = $divideAmount;
				        	$ded_date = $this->cut_off_day($emp_Data[0]['company_code'],$emp_Data[0]['bunit_code']);
			               	$start_date = date('Y-m-d',strtotime('2019-06-30'));

			        		for($x = 0 ;$x <= $div;$x++)
			               	{ 
			               		$ded_days_count = 2;
				        		if($emp_Data[0]['mhhp_1st_cutoff']== '0.00')
				        		{
				               		$ded_days_count = 1;
				        		}

				        		if($x == 0):
				        			echo '<tr style="font-size:13px">';
					                echo '<td>'.$start_date.'</td>';
					                echo '<td style="text-align: right;"></td>';
					                echo '<td style="text-align: right;">-----</td>';
					                echo '<td style="text-align: right;">'.number_format(abs($start_bal),2).'</td>';
					                echo '</tr>';
					            else:
				               	for ($z=1; $z <= $ded_days_count; $z++)
				               	{ 

				               		if($start_bal > 0):
				               		if($ded_days_count == 1):
				               		$start_bal = $start_bal - $emp_Data[0]['mhhp_2nd_cutoff'];
				               		echo '<tr style="font-size:13px">';
					                echo '<td>'.date('Y-m',strtotime($start_date)).'-'.str_pad($ded_date[0]['pDaySC'],2,"0",STR_PAD_LEFT).'</td>';
					                echo '<td style="text-align: right;">'.number_format($emp_Data[0]['mhhp_2nd_cutoff'],2).'</td>';
					                echo '<td style="text-align: right;">-----</td>';
					                echo '<td style="text-align: right;">'.number_format(abs($start_bal),2).'</td>';
					                echo '</tr>';
				               		else:
				               		$start_bal = $start_bal - $emp_Data[0]['mhhp_2nd_cutoff'];
					               		if($z == 1):
					               		echo '<tr style="font-size:13px">';
						                echo '<td>'.date('Y-m',strtotime($start_date)).'-'.str_pad($ded_date[0]['pDayFC'],2,"0",STR_PAD_LEFT).'</td>';
						                echo '<td style="text-align: right;">'.number_format($emp_Data[0]['mhhp_1st_cutoff'],2).'</td>';
						                echo '<td style="text-align: right;">-----</td>';
						                echo '<td style="text-align: right;">'.number_format(abs($start_bal),2).'</td>';
						                echo '</tr>';
					               		else:
						                echo '<tr style="font-size:13px">';
						                echo '<td>'.date('Y-m',strtotime($start_date)).'-'.str_pad($ded_date[0]['pDaySC'],2,"0",STR_PAD_LEFT).'</td>';
						                echo '<td style="text-align: right;">'.number_format($emp_Data[0]['mhhp_2nd_cutoff'],2).'</td>';
						                echo '<td style="text-align: right;">-----</td>';
						                echo '<td style="text-align: right;">'.number_format(abs($start_bal),2).'</td>';
						                echo '</tr>';
					               		endif;
				               		endif;
				               		endif;

				               		if($ded_days_count == $z)
				               		{
				               			$start_date = date('Y-m-d',strtotime('+1 month',strtotime($start_date)));
				               		}
				               	}
			               		endif;
				              
				            }   

			        	}	
		            }
		             echo 
		             '</tbody>

                    </table>
                   </div>
				  </div>
				<div class="modal-footer">
			  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		   </div>
		  </div>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				$(".tble-data").DataTable({
					  "ordering": false,
					  "pageLength": 50
				});
			});
		</script>';

    }

    public function modal_unaudited_mod(){

    	/*echo '<div class="modal-dialog modal-md" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">MHHP UNAUDITED REPORT</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">
				 			<div class="table-responsive">
              					<table style="width:100%;">
								  <tr>
								  	<td>
								    	<label>Date:</label>
				                    </td>
								    <td>
								    	<input type="text" id="datepicker" class="form-control unaudited_datepicker" placeholder="Date"/>
				                    </td>
								  </tr>
								  <tr>
								    <td>
								    	<label>Deduction Type:</label>
				                    </td>
								    <td>
								    	<select class="form-control unaudited_type" id="sel1">
								    	 	<option value="deduction_summary">Deduction Summary</option>
										    <option value="deduction_slip">Deduction Slip</option>
									  	</select>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label>Covered:</label>
				                    </td>
								    <td>
								    	<select class="form-control partner_type unaudited_pcc" id="sel1">
										    <option value="by_all">All Home Partners</option>
										    <option value="by_pcc">PCC Code</option>
									  	</select>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label class="pcc_lbl">PCC Code:</label>
				                    </td>
								  	<td>
								  		<textarea class="form-control pcc_text_area" rows="3" id="comment" placeholder="33,36"></textarea>
								  	</td>
								  </tr>

								</table>
                 			</div>
				        </div>
				   <div class="modal-footer">
				  <button class="btn btn-primary unaudited_re" type="button">Proceed</button>
			    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		      </div>
		 	</div>
		</div>';*/

		echo '<div class="modal-dialog modal-md" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">MHHP UNAUDITED REPORT</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">
				 			<div class="table-responsive">
              					<table style="width:100%;">
								  	<tr>
									    <td>
									    	<label>Deduction Type:</label>
					                    </td>
									    <td>
									    	<div class="col-sm-12">
										    	<select class="form-control unaudited_type" id="sel1">
										    	 	<option value="deduction_summary">Deduction Summary</option>
												    <option value="deduction_slip">Deduction Slip</option>
											  	</select>
										  	</div>
										</td>
								  	</tr>
								  	<tr>
									  	<td>
									    	<label>Date:</label>
					                    </td>
									    <td>
									    	<div class="col-sm-12">
									    		<input type="text" id="datepicker" class="form-control unaudited_datepicker" placeholder="Date"/>
									    	<div class="col-sm-12">
					                    </td>
								  	</tr>
								  	<tr>
									  	<td>
									    	<label>Payroll:</label>
					                    </td>
									    <td>
									    	<div class="col-sm-12" id="payroll_select">
										      	<select class="form-control option" id="payroll" disabled>
										        	<option></option>
										      	</select>
										    </div>
					                    </td>
								  	</tr>
								</table>
                 			</div>
				        </div>
				   <div class="modal-footer">
				  <button class="btn btn-primary unaudited_re" type="button">Proceed</button>
			    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		      </div>
		 	</div>
		</div>';

		echo '
			<style>
				.pcc_text_area,.pcc_lbl{
					display:none;
				}
			</style>
		';

		echo '
		<script>
				$("#datepicker").on("change", function() {
			    
			        var datepicker = $("#datepicker").val();
			        
			        console.log(datepicker);
			        var date 	= datepicker;
			       
		          	$.post("<?php echo site_url("printing/payroll_list") ?>",{
		          		"date":date
		          	},function(datas){
		            	$("#payroll_select").html(datas);
		          	});
          			
			        
			  	});

				$("#datepicker").datepicker();

 				$(".partner_type").on("change",function(){
					var type = $(this).val();
					if(type == "by_all"){
						$(".pcc_lbl").toggle("slidedown");
						$(".pcc_text_area").toggle("slidedown");
					}else{
						$(".pcc_lbl").toggle("slidedown");
						$(".pcc_text_area").toggle("slidedown");
					}
				});

				$(".unaudited_re").on("click",function(){
					var date = $(".unaudited_datepicker").val();
					var type = $(".unaudited_type").val();
					var pcc_all  = $(".unaudited_pcc").val();
					var pcc_code = $(".pcc_text_area").val();
					if(date=="")
					{
						alertify.success("Please select a date");
						return false;
					}

					if(type=="deduction_summary")
					{
							if(pcc_all=="by_all")
							{
								 window.open("'.base_url().'accounting/report_unaudited_all_r?date="+date,"_blank");
	   						}
	   						else
	   						{
	   						if(pcc_code=="")
		   					{
		   							alertify.success("Please enter a PCC code");
									return false;
		   					}
	   						else
		   					{
		   							window.open("'.base_url().'accounting/report_unaudited_bypcc_r?date="+date+"&pcc_code="+pcc_code,"_blank");	
		   					}
	   					}
					}

					if(type=="deduction_slip")
					{
						if(pcc_all=="by_all")
						{
							window.open("'.base_url().'accounting/report_slip_all_r?date="+date,"_blank")
						}
						else
						{
							if(pcc_code=="")
							{
								alertify.success("Please enter a PCC code");
								return false;
							}
							else
							{
								window.open("'.base_url().'accounting/report_slip_bypcc_r?date="+date+"&pcc_code="+pcc_code,"_blank")
							}	
						}	

					}

					
   				});
			</script>
		';
    }
    public function modal_unaudited_mod_v1(){
		?>
			<div class="modal-dialog modal-md" role="document">
			 	<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">MHHP UNAUDITED REPORT</h5>
							<button class="close" type="button" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
					</div>
					<div class="modal-body">
			 			<div class="table-responsive">
          					<table style="width:100%;">
							  	<tr>
								    <td>
								    	<label>Deduction Type:</label>
				                    </td>
								    <td>
								    	<div class="col-sm-12">
									    	<select class="form-control unaudited_type" id="summary_type">
									    	 	<option value="deduction_summary">Deduction Summary</option>
											    <!-- <option value="deduction_slip">Deduction Slip</option> -->
										  	</select>
									  	</div>
									</td>
							  	</tr>
							  	<tr>
								  	<td>
								    	<label>Date:</label>
				                    </td>
								    <td>
								    	<div class="col-sm-12" id="date_prl">
								    		<input type="text" id="datepicker" autocomplete="off" class="form-control unaudited_datepicker" placeholder="Date"/>
								    	<div class="col-sm-12">
				                    </td>
							  	</tr>
							  	<tr>
								  	<td>
								    	<label>Payroll:</label>
				                    </td>
								    <td>
								    	<div class="col-sm-12" id="payroll_select">
									      	<select class="form-control option" id="payroll" disabled>
									        	<option></option>
									      	</select>
									    </div>
				                    </td>
							  	</tr>
							</table>
             			</div>
			        </div>
					<div class="modal-footer">
				    	<button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
					  	<button class="btn btn-primary unaudited_re" id="summary" type="button">Proceed</button>
		      		</div>
		      	</div>
		 	</div>

		 	<script type="text/javascript">
		 		

				$("#datepicker").on("change", function() {
			    
			        var date  = $("#datepicker").val();
			       	var img   = 'Please wait <img src="<?php echo base_url().'assets/svg/loader/loader_green.svg' ?>" width="40px;">';
                  
              $('#payroll_select').html(img);
		          $.post("<?php echo site_url("printing/payroll_list") ?>",{
		          		"date":date
		          	},function(datas){
		            	$("#payroll_select").html(datas);
		          	});
			  	});

			  	$('#datepicker').on('change', function(event) {
				      document.getElementById("payroll_select").disabled = false;
				});

				$("#datepicker").datepicker();

				$("#summary").on("click", function() {

			    var date 	  	  	= $("#datepicker").datepicker("getDate");
					var dateString 	  = $.datepicker.formatDate("yy-mm-dd", date);
					var sum_type      = $('#summary_type').val();
			    var payroll       = $('#payroll').val();
					var date1 		  	= new Date(date);
					var day   		  	= (date1.getDate() < 10 ? '0' : '') + date1.getDate();
			    var img           = '<center><div class="loading">Please wait &nbsp;<i class="fas fa-spinner fa-spin"></i></div></center>';


					if(date != ''){
						if(day == '15' || day == '30' || day == '05' || day == '20' || day == '28' || day == '29'){

							window.open('<?php echo base_url("accounting/mhhp_summary")?>/'+dateString+'/'+sum_type+'/'+payroll+'');
							// window.location.reload();
						}else{
							Swal.fire({
							  	// icon: 'error',
							  	title: 'Error',
							  	text: 'Please select the correct Deduction Date!',
							  	footer: '<a href="">Wrong Deduction date</a>'
							})
						}

					}else{
						Swal.fire({
						  	// icon: 'error',
						  	title: 'Error',
						  	text: 'Please select the date!',
						  	// footer: '<a href="">Wrong Deduction date</a>'
						})
					}
				});
			</script>
		<?php
    }

    public function getunaudited_all_mod(){
        $this->db->select('*');
        $this->db->from('ebs.mhhp_balance as bal');
        $this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.mh_employee','inner');
 				$this->db->where('bal.mhhp_current_bal > 0');
 				$this->db->group_by('CONCAT(emp.company_code,emp.bunit_code)');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getdept_unaudited_all_mod($locbu,$comp){

        $this->db->select('*');
        $this->db->from('ebs.mhhp_balance as bal');
        // $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.mh_employee','inner');
        $this->db->join('pis.locate_business_unit as locbu', 'locbu.bcode = CONCAT(emp.company_code,emp.bunit_code)','inner');
		// $this->db->join('pis.locate_department as locdept', 'locdept.dcode = CONCAT(emp.company_code,emp.bunit_code,emp.dept_code)','inner');
		 		$this->db->where('emp.company_code', $comp);
		 		$this->db->where('emp.bunit_code', $locbu);
		 		$this->db->where('emp.payroll_no != " "');
		 		$this->db->where('bal.mhhp_current_bal > 0');
		 		$this->db->where('msf.mh_paymentmode = "DEDUCTION"');
		 		$this->db->group_by('emp.emp_id');
        $query = $this->db->get();	
        return $query->result_array();
    }

    public function getunaudited_bypcc_mod($pcc_code){
    	
    	$pcc_ex = explode(",",$pcc_code);

        $this->db->select('*');
        $this->db->from('ebs.mhhp_balance as bal');
        $this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.mh_employee','inner');

        // $this->db->where('emp.payroll_no','100108');
        $this->db->where_in('emp.pcc', $pcc_ex); 
 		$this->db->where('bal.mhhp_current_bal > 0');
 		$this->db->group_by('emp.bunit_code');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function getdept_unaudited_bypcc_mod($locbu,$date,$pcc_code){
    	$pcc_ex = explode(",",$pcc_code);
    	// var_dump($pcc_ex);
    	$this->db->select('*');
        $this->db->from('ebs.mhhp_balance as bal');
        // $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('ebs.mhhp_request_msfl as msf', 'msf.mh_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.mh_employee','inner');
        $this->db->join('pis.locate_business_unit as locbu', 'locbu.bcode = CONCAT(emp.company_code,emp.bunit_code)','inner');
		// $this->db->join('pis.locate_department as locdept', 'locdept.dcode = CONCAT(emp.company_code,emp.bunit_code,emp.dept_code)','inner');
 		$this->db->where('emp.bunit_code', $locbu);
 		$this->db->where('emp.payroll_no != " "');
 		$this->db->where('bal.mhhp_current_bal > 0');
        $this->db->where_in('emp.pcc', $pcc_ex); 
 		$this->db->where('msf.mh_paymentmode = "DEDUCTION"');
 		$this->db->group_by('emp.emp_id');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_cutoff($cc,$bc,$date){
	    	$cutoff = array();
	    	$this->db->select('*');
	      $this->db->from('pis.cut_off as cut');
	 			$this->db->where('cut.cc = '.$cc);
	 			$this->db->where('cut.bc = '.$bc);
	 			$this->db->limit(1);
	      $query = $this->db->get();

        $result = $query->result_array();
        if(empty($result)):
	        $this->db->select('*');
	        $this->db->from('pis.cut_off as cut');
			 		$this->db->where('cut.cc = "" ');
			 		$this->db->where('cut.bc = "" ');
			 		$this->db->limit(1);
		      $query = $this->db->get();
		    endif;
    	$data = $query->result_array();
    	
    	foreach ($data as $value):
		    
		    // var_dump(date('d',strtotime($date)));
	    	if(date('d',strtotime($date)) == $value['pDayFC']):
	    		
	    			$cutoff[] = $value['pDayFC'];
	    	elseif(date('d',strtotime($date)) == $value['pDaySC']):
	    		
	    			$cutoff[] = $value['pDaySC'];
	    	else:
	    
	    		if(date('d',strtotime($date)) == '15')
	    		{
	    			$cutoff[] = '15';
	    		}

	    		if(date('d',strtotime($date)) == '30')
	    		{
	    			$cutoff[] = '30';
	    		}

	    		if(date('d',strtotime($date)) == '28')
	    		{
	    			$cutoff[] = '28';
	    		}
	    		if(date('d',strtotime($date)) == '29')
	    		{
	    			$cutoff[] = '29';
	    		}

	    		if(date('d',strtotime($date)) == '05')
	    		{
	    			$cutoff[] = '05';
	    		}

	    		if(date('d',strtotime($date)) == '20')
	    		{
	    			$cutoff[] = '20';
	    			// var_dump('hi');
	    		}
	    		
	    	endif;

    	endforeach;
    		// var_dump($cutoff);
        return $cutoff;
    }

    public function get_conso($date,$emp_id){

        $this->db->select('bal.ldg_balance,bal.ldg_payrollid');
        $this->db->from('ebs.ebm_consolidated_ledger as bal');
 		$this->db->where('bal.ldg_type', 'MHHP-marcela');
 		$this->db->where('bal.ldg_hrmsid', $emp_id);
 		$this->db->where('bal.ldg_deduction_date', $date);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_conso_date($emp_id,$date){
    	$array = array();
        $this->db->select('SUBSTRING(bal.ldg_deduction_date,9,2) as date');
        $this->db->from('ebs.ebm_consolidated_ledger as bal');
 		$this->db->where('bal.ldg_type', 'MHHP-marcela');
 		$this->db->where('bal.ldg_hrmsid', $emp_id);
 		$this->db->group_by('SUBSTRING(bal.ldg_deduction_date,9,2)');
        $query = $this->db->get();
        foreach ($query->result_array() as $value):
        	if($value['date'] == substr($date, 8,2))
        	{
        		return $value['date'];
        	}
        endforeach;
    }

    public function prev_cutoff($date){

        if($date == '05'):
        	return '20';
        elseif($date == '20'):
        	return '5';
        elseif($date == '15'):
        	return '30';
        elseif($date == '30'):
        	return '15';
        endif;
    }

    public function modal_unaudited_habitat_mod(){
    	echo '<div class="modal-dialog modal-md" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">HABITAT UNAUDITED REPORT</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">
				 			<div class="table-responsive">
              					<table style="width:100%;">
								  <tr>
								  	<td>
								    	<label>Date:</label>
				                    </td>
								    <td>
								    	<input type="text" id="datepicker" class="form-control unaudited_datepicker" placeholder="Date"/>
				                    </td>
								  </tr>
								  <tr>
								    <td>
								    	<label>Deduction Type:</label>
				                    </td>
								    <td>
								    	<select class="form-control unaudited_type" id="sel1">

								    	 	<option value="deduction_summary">Deduction Summary</option>
										    <option value="deduction_slip">Deduction Slip</option>
										   
									  	</select>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label>Covered:</label>
				                    </td>
								    <td>
								    	<select class="form-control partner_type unaudited_pcc" id="sel1">
										    <option value="by_all">All Home Partners</option>
										    <option value="by_pcc">PCC Code</option>
									  	</select>
									</td>
								  </tr>
								  <tr>
								    <td>
								    	<label class="pcc_lbl">PCC Code:</label>
				                    </td>
								  	<td>
								  		<textarea class="form-control pcc_text_area" rows="3" id="comment" placeholder="33,36"></textarea>
								  	</td>
								  </tr>

								</table>
                 			</div>
				        </div>
				   <div class="modal-footer">
				  <button class="btn btn-primary unaudited_re" type="button">Proceed</button>
			    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		      </div>
		 	</div>
		</div>';

		echo '
			<style>
				.pcc_text_area,.pcc_lbl{
					display:none;
				}
			</style>
		';

		echo '
		<script>
				$("#datepicker").datepicker();

 				$(".partner_type").on("change",function(){
					var type = $(this).val();
					if(type == "by_all"){
						$(".pcc_lbl").toggle("slidedown");	
						$(".pcc_text_area").toggle("slidedown");
					}else{
						$(".pcc_lbl").toggle("slidedown");
						$(".pcc_text_area").toggle("slidedown");
					}
				});

				$(".unaudited_re").on("click",function(){
					var date = $(".unaudited_datepicker").val();
					var type = $(".unaudited_type").val();
					var pcc_all  = $(".unaudited_pcc").val();
					var pcc_code = $(".pcc_text_area").val();
					if(date=="")
					{
						alertify.success("Please select a date");
						return false;
					}

					if(type=="deduction_summary")
					{
							if(pcc_all=="by_all")
							{
								 window.open("'.base_url().'accounting/hb_report_all_r?date="+date,"_blank");
	   						}
	   					else
	   					{
	   						if(pcc_code=="")
		   					{
		   							alertify.success("Please enter a PCC code");
									return false;
		   					}
	   						else
		   					{
		   							window.open("'.base_url().'accounting/hb_report_bypcc_r?date="+date+"&pcc_code="+pcc_code,"_blank");	
		   					}
	   					}
					}

					if(type=="deduction_slip")
					{
						if(pcc_all=="by_all")
						{
							window.open("'.base_url().'accounting/hb_slip_all_r?date="+date,"_blank")
						}
						else
						{
							if(pcc_code=="")
							{
								alertify.success("Please enter a PCC code");
								return false;
							}
							else
							{
								window.open("'.base_url().'accounting/hb_slip_bypcc_r?date="+date+"&pcc_code="+pcc_code,"_blank")
							}	
						}	

					}
   				});
			</script>
		';
    }

    public function hb_getunaudited_all_mod(){
    		$this->db->select('*');
        $this->db->from('ebs.mhhp_balance_hb as hb_bal');
        $this->db->join('ebs.mhhp_hbt_msfl as hb_msf', 'hb_msf.hbt_reqcode = hb_bal.hb_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = hb_msf.hbt_emp','inner');
 				$this->db->where('hb_bal.hb_balance > 0');
 				$this->db->group_by('emp.bunit_code');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function hb_getdept_unaudited_all_mod($locbu,$comp){

        $this->db->select('*');
        $this->db->from('ebs.mhhp_balance_hb as hb_bal');
        // $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('ebs.mhhp_hbt_msfl as hb_msf', 'hb_msf.hbt_reqcode = hb_bal.hb_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = hb_msf.hbt_emp','inner');
        $this->db->join('pis.locate_business_unit as locbu', 'locbu.bcode = CONCAT(emp.company_code,emp.bunit_code)','inner');
		// $this->db->join('pis.locate_department as locdept', 'locdept.dcode = CONCAT(emp.company_code,emp.bunit_code,emp.dept_code)','inner');
 		$this->db->where('emp.bunit_code', $locbu);
 		$this->db->where('emp.payroll_no != " "');
 		$this->db->where('hb_bal.hb_balance > 0');
 		$this->db->where('hb_msf.hbt_paymentmode = "DEDUCTION"');
 		$this->db->group_by('emp.emp_id');
        $query = $this->db->get();	
        return $query->result_array();
     
    }

    public  function locb($locbu,$comp){

    	$this->db->select('*');
    	$this->db->from('pis.locate_department as locbu');
    	$this->db->where('locbu.bunit_code', $locbu);
    	$query = $this->db->get();	
        return $query->result_array();

    }

    public  function locc($comp,$locbu,$deptcode){

    	$this->db->select('*');
    	$this->db->from('pis.locate_department as locbu');
    	$this->db->where('locbu.company_code', $comp);
    	$this->db->where('locbu.bunit_code', $locbu);
    	$this->db->where('locbu.dept_code', $deptcode);
    	$query = $this->db->get();	
        return $query->result_array();

    }

     public function hb_getdept_unaudited_bypcc_mod($locbu,$date,$pcc_code){
    	$pcc_ex = explode(",",$pcc_code);
    	// var_dump($pcc_ex);

    	$this->db->select('*');
        $this->db->from('ebs.mhhp_balance_hb as hb_bal');
        // $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = bal.mhhp_serial_code','inner');
        $this->db->join('ebs.mhhp_hbt_msfl as hb_msf', 'hb_msf.hbt_reqcode = hb_bal.hb_serial_code','inner');
        $this->db->join('pis.employee3 as emp', 'emp.emp_id = hb_msf.hbt_emp','inner');
        $this->db->join('pis.locate_business_unit as locbu', 'locbu.bcode = CONCAT(emp.company_code,emp.bunit_code)','inner');
		// $this->db->join('pis.locate_department as locdept', 'locdept.dcode = CONCAT(emp.company_code,emp.bunit_code,emp.dept_code)','inner');
 		$this->db->where('emp.bunit_code', $locbu);
 		$this->db->where('emp.payroll_no != " "');
 		$this->db->where('hb_bal.hb_balance > 0');
 		$this->db->where_in('emp.pcc', $pcc_ex); 
 		$this->db->where('hb_msf.hbt_paymentmode = "DEDUCTION"');
 		$this->db->group_by('emp.emp_id');
        $query = $this->db->get();	
        return $query->result_array();
    }

    public function hb_getHomePartners_mod(){
    	$this->db->select('*');
		$this->db->from('ebs.mhhp_hbt_amortsched as amrt');
		$this->db->join('ebs.mhhp_hbt_msfl as msf', 'msf.hbt_reqcode = amrt.hbt_reqcode','inner');
		// $this->db->join('ebs.mhhp_deduction as mhded', 'mhded.mhhp_serial_code = amrt.mhhp_serial_code','inner');
		$this->db->join('ebs.mhhp_balance_hb as bal', 'bal.hb_serial_code = amrt.hbt_reqcode','inner');
		$this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.hbt_emp','inner');
		$query = $this->db->get();
        return $query->result_array();
    }

    public function hb_modal_employeee_monitoring_mod($post_id){
    	$this->db->select('*');
		$this->db->from('ebs.mhhp_hbt_amortsched as amrt');
		$this->db->join('ebs.mhhp_hbt_msfl as msf', 'msf.hbt_reqcode = amrt.hbt_reqcode','inner');
		// $this->db->join('ebs.mhhp_hbt_deduction as mhded', 'mhded.hbt_reqcode = amrt.hbt_reqcode','inner');
		$this->db->join('ebs.mhhp_balance_hb as bal', 'bal.hb_serial_code = amrt.hbt_reqcode','inner');
		$this->db->join('pis.employee3 as emp', 'emp.emp_id = msf.hbt_emp','inner');
		$this->db->where('msf.hbt_reqcode', $post_id);
		// $this->db->order_by('mhded.hdT_cutoff', 'desc');
		$query = $this->db->get();
        $emp_Data = $query->result_array();
       

       $running_bal = $emp_Data[0]['hb_balance'];
           echo '
		<div class="modal-dialog modal-lg" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">'.$emp_Data[0]['name'].'-'.$emp_Data[0]['hbt_reqcode'].'</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">';
				echo '<div class="table-responsive">
                <table class="table table-bordered tble-data" id="" width="100%" cellspacing="0">
                  <thead>
                  <tr style="font-size:11.5px">
                    <th>DEDUCTION DATE</th>
                    <th style="text-align: right;">AMT DEDUCTED</th>
                    <th style="text-align: right;">DEDUCTION CODE</th>
                    <th style="text-align: right;">BALANCE</th>
                   </tr>
                  </thead>
                <tbody>';
                	$this->db->select('*');
					$this->db->from('ebs.mhhp_hbt_deduction as mhded');
					$this->db->where('mhded.hbt_reqcode', $post_id);
					// $this->db->order_by('mhded.hdT_cutoff', 'desc');
					$query2 = $this->db->get();
			        $ded_Data = $query2->result_array();
			        if(!empty($ded_Data)){
		               	foreach($ded_Data as $value){  
		               	$row_balance = $running_bal - $value['hdT_amount'];
		                  echo '<tr style="font-size:13px">';
		                    echo '<td>'.$value['hdT_cutoff'].'</td>';
		                    echo '<td style="text-align: right;">'.number_format($value['hdT_amount'],2).'</td>';
		                    echo '<td style="text-align: right;">'.$value['hdT_dedcode'].'</td>';
		                    echo '<td style="text-align: right;">'.number_format(abs($row_balance),2).'</td>';
		                  echo '</tr>';
		                  $running_bal = $row_balance;
		                }
		             }
		            	echo '</tbody>
                    </table>
                   </div>
				  </div>
				<div class="modal-footer">
			  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		   </div>
		  </div>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				$(".tble-data").DataTable({
					  "ordering": false,
					  "pageLength": 50
				});
			});
		</script>';
    }

    public function mh_walkinpay_mod($post_id){

    	$this->db->select('*');
			$this->db->from('ebs.mhhp_balance as bal');
			$this->db->join('pis.employee3 as emp', 'emp.emp_id = bal.emp_id','inner');
			$this->db->where('bal.mhhp_serial_code', $post_id);
			$query = $this->db->get();
        $emp_Data = $query->result_array();

    	echo '<div class="modal-dialog modal-md" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title wlkin_acc_no" id='.$post_id.'>'.$emp_Data[0]['name'].'-'.$post_id.'</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">
				 			<div class="table-responsive">
              					<table style="width:100%;">
								  <tr>
								  	<td>
								    	<label>Date:</label>
				                    </td>
								    <td>
								    	<input type="text" id="datepicker" class="form-control wlk_datepicker" placeholder="Date"/>
				                    </td>
								  </tr>
								  <tr>
								    <td>
								    	<label>1st Payable:</label>
				                    </td>
								    <td>
								    	<input type="text" value='.number_format($emp_Data[0]['mhhp_1st_cutoff'],2).' id="datepicker" class="form-control wlk_amt_amort" placeholder="Date" disabled="true"/>
									</td>
								  </tr>
								  <tr>
								    <td>
								    	<label>2nd Payable:</label>
				                    </td>
								    <td>
								    	<input type="text" value='.number_format($emp_Data[0]['mhhp_2nd_cutoff'],2).' id="datepicker" class="form-control wlk_amt_amort" placeholder="Date" disabled="true"/>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label>Payment Amount:</label>
				                    </td>
								    <td>
								    	<input type="text" id="" class="form-control wlkpay_amt" placeholder="Amount"/>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label>OR Number:</label>
				                    </td>
								    <td>
								    	<input type="text" id="" class="form-control wlk_orno" placeholder="OR Number..."/>
									</td>
								  </tr>
								</table>
                 			</div>
				        </div>
				   <div class="modal-footer">
				  <button class="btn btn-primary mh_save_partial" type="button">Proceed</button>
			    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		      </div>
		 	</div>
		</div>';


		echo '
		<script>
			$(".wlkpay_amt").maskMoney({allowZero:false, allowNegative:true, defaultZero:false});	
			$("#datepicker").datepicker();

			$(".mh_save_partial").on("click",function(){
				var wlkin_acc_no = $(".wlkin_acc_no").attr("id");
				var date_v =  $(".wlk_datepicker").val();
				
				var payamt_v =  $(".wlkpay_amt").val();
				var ornumber_v = $(".wlk_orno").val();

				if(payamt_v == ""){
					alertify.warning("Some fields are Empty");
				}
				else{

					$.ajax({
						url:"'.base_url().'/accounting/mh_sub_walkinpay_r",
						method:"POST",
						cache: false,
						data:{
								p_wlkin_acc_no:wlkin_acc_no,
								p_date_v:date_v,
								p_payamt_v:payamt_v,
								p_ornumber_v:ornumber_v
							},
								success:function(data)
							{ 
								alert("Payment Successfully Forwarded");
								$("#modal").modal("hide"); 
								location.reload();
						}
					});
			
				}

			});	

		</script>';
    }


    public function sub_walkinpay_mod($p_wlkin_acc_no,$p_date_v,$p_payamt_v,$p_ornumber_v){
    	
	    	$this->db->select('*');
				$this->db->from('ebs.mhhp_balance as bal');
				$this->db->join('pis.employee3 as emp', 'emp.emp_id = bal.emp_id','inner');
				// $this->db->join('ebs.mhhp_deduction as mhhpded', 'mhhpded.mhhp_serial_code = bal.mhhp_serial_code','inner');
				$this->db->where('bal.mhhp_serial_code', $p_wlkin_acc_no);
				$query = $this->db->get();
        $emp_Data = $query->result_array();

        $emp_dept = $emp_Data[0]['company_code'].$emp_Data[0]['bunit_code'].$emp_Data[0]['dept_code'];
    		$data = array(
			       'ded_empdept'				=> $emp_dept,
			       'mhhp_serial_code'   => $p_wlkin_acc_no,
			       'ded_emp'						=> $emp_Data[0]['emp_id'],
			       'ded_deductiondate'  => date("Y-m-d",strtotime($p_date_v)),
			       'ded_amount'					=> floatval(str_replace(',','', $p_payamt_v)),
			       'ded_datededucted'   => date("Y-m-d"),
			       'ded_assist'  				=> $this->session->userdata('emp_id'),
			       'ded_deductioncode'  => 'WKN-'.$p_ornumber_v,
			       'ded_paymenttype'    => 'AMORTIZATION',
			       'ded_payable'        => floatval($emp_Data[0]['mhhp_current_bal'])-floatval(str_replace(',','', $p_payamt_v)) 
				);
				$this->db->insert('ebs.mhhp_deduction', $data);
				$data1 = array('mhhp_current_bal' => floatval($emp_Data[0]['mhhp_current_bal'])-floatval(str_replace(',','', $p_payamt_v)));
				$this->db->where('mhhp_serial_code', $p_wlkin_acc_no);
				$this->db->update('mhhp_balance', $data1);
    }

    public function hb_walkinpay_mod($post_id){

    	$this->db->select('*');
				$this->db->from('ebs.mhhp_balance_hb as bal');
				$this->db->join('pis.employee3 as emp', 'emp.emp_id = bal.emp_id','inner');
				$this->db->where('bal.hb_serial_code', $post_id);
				$query = $this->db->get();
        $emp_Data = $query->result_array();

    	echo '<div class="modal-dialog modal-md" role="document">
			 <div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title wlkin_acc_no" id='.$post_id.'>'.$emp_Data[0]['name'].'-'.$post_id.'</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						</div>
						<div class="modal-body">
				 			<div class="table-responsive">
              					<table style="width:100%;">
								  <tr>
								  	<td>
								    	<label>Date:</label>
				                    </td>
								    <td>
								    	<input type="text" id="datepicker" class="form-control hb_wlk_datepicker" placeholder="Date"/>
				                    </td>
								  </tr>
								  <tr>
								    <td>
								    	<label>Payable:</label>
				                    </td>
								    <td>
								    	<input type="text" value='.number_format($emp_Data[0]['hb_cutoff_amt'],2).' id="datepicker" class="form-control hb_wlk_amt_amort" placeholder="Date" disabled="true"/>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label>Payment Amount:</label>
				                    </td>
								    <td>
								    	<input type="text" id="" class="form-control hb_wlkpay_amt" placeholder="Amount"/>
									</td>
								  </tr>
								  <tr>
								  	<td>
								    	<label>OR Number:</label>
				                    </td>
								    <td>
								    	<input type="text" id="" class="form-control hb_wlk_orno" placeholder="OR Number..."/>
									</td>
								  </tr>
								</table>
                 			</div>
				        </div>
				   <div class="modal-footer">
				  <button class="btn btn-primary hb_save_partial" type="button">Proceed</button>
			    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
		      </div>
		 	</div>
		</div>';

		echo '<script>
			$(".hb_wlkpay_amt").maskMoney({allowZero:false, allowNegative:true, defaultZero:false});	
			$("#datepicker").datepicker();

			$(".hb_save_partial").on("click",function(){
				var p_wlkin_acc_no    = $(".wlkin_acc_no").attr("id");
				var hb_wlk_datepicker = $(".hb_wlk_datepicker").val();
				var hb_wlkpay_amt     = $(".hb_wlkpay_amt").val();
				var hb_wlk_orno       = $(".hb_wlk_orno").val();

				if(hb_wlkpay_amt==""){
					alertify.warning("Some fields are Empty");
				}else{
					$.ajax({
						url:"'.base_url().'/accounting/hb_sub_walkinpay_r",
						method:"POST",
						cache: false,
						data:{
								p_p_wlkin_acc_no:p_wlkin_acc_no,
								p_hb_wlk_datepicker:hb_wlk_datepicker,
								p_hb_wlkpay_amt:hb_wlkpay_amt,
								p_hb_wlk_orno:hb_wlk_orno
							},
								success:function(data)
							{ 
								alert("Payment Successfully Forwarded");
								$("#modal").modal("hide"); 
								location.reload();
						}
					});
				}

			});
         </script>';
    }

    public function hb_sub_walkinpay_mod($p_p_wlkin_acc_no,$p_hb_wlk_datepicker,$p_hb_wlkpay_amt,$p_hb_wlk_orno){
    	
    	$this->db->select('*');
		$this->db->from('ebs.mhhp_balance_hb as bal');
		$this->db->join('pis.employee3 as emp', 'emp.emp_id = bal.emp_id','inner');
		// $this->db->join('ebs.mhhp_hbt_deduction as mhhpded', 'mhhpded.hbt_reqcode = bal.hb_serial_code','inner');
		$this->db->where('bal.hb_serial_code', $p_p_wlkin_acc_no);
		$query = $this->db->get();
        $emp_Data = $query->result_array();

        $emp_dept = $emp_Data[0]['company_code'].$emp_Data[0]['bunit_code'].$emp_Data[0]['dept_code'];
        
        $data = array(
	      'hbt_reqcode' 	=> $p_p_wlkin_acc_no,
	      'hdT_emp'     	=> $emp_Data[0]['emp_id'],
	      'hdT_empdept' 	=> $emp_dept,
	      'hdT_cutoff'  	=> date("Y-m-d",strtotime($p_hb_wlk_datepicker)),
	      'hdT_amount'  	=> str_replace(',','', $p_hb_wlkpay_amt),
	      'hdT_assist'  	=> $this->session->userdata('emp_id'),
	      'hdT_datetime'	=> date("Y-m-d"),
	      'hdT_dedcode' 	=> 'WKN-'.$p_hb_wlk_orno
		);
		$this->db->insert('ebs.mhhp_hbt_deduction', $data);

		$data1 = array(
		  'hb_current_bal'  => $emp_Data[0]['hb_current_bal']-str_replace(',','', $p_hb_wlkpay_amt)
		);

		$this->db->where('hb_serial_code', $p_p_wlkin_acc_no);
		$this->db->update('mhhp_balance_hb', $data1);
    }

     public function mh_chart_mod(){
     	$total_re_bal = 0;
		$total_paid_bal = 0;
		$total= 0;
		$this->db->select('*');
		$this->db->from('ebs.mhhp_balance as bal');
		$this->db->join('ebs.ebm_consolidated_ledger as ebmcons', 'ebmcons.ldg_hrmsid = bal.emp_id','inner');
		$this->db->where('ebmcons.ldg_type', 'MHHP-marcela');
		$query = $this->db->get();
        $emp_Data = $query->result_array();
   		foreach ($emp_Data as $value){
   			// $total_re_bal += $value['mhhp_current_bal'];
   			$total_paid_bal += $value['ldg_credit'];

   		}


   		$this->db->select('*');
		$this->db->from('ebs.mhhp_balance as bal');
		$query = $this->db->get();
        $emp_Data = $query->result_array();
   		foreach ($emp_Data as $value){
   			$total_re_bal += $value['mhhp_current_bal'];
   		}

   		

	    echo '<div class="col-lg-4">
            <div class="card mb-3">
              <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                Pie Chart</div>
              <div class="card-body">
                <canvas id="myPieChart" width="100%" height="100"></canvas>
              </div>
              <div class="card-footer small text-muted"></div>
            	</div>
        	</div>';
     
        echo '<script>
				Chart.defaults.global.defaultFontFamily = "-apple-system,system-ui","BlinkMacSystemFont","Segoe UI","Roboto","Helvetica Neue","Arial","sans-serif";
				Chart.defaults.global.defaultFontColor = "#292b2c";	
				var ctx = document.getElementById("myPieChart");
				var myPieChart = new Chart(ctx, {
				type: "pie",
				data: {
				labels: ["Remaining Amount","Paid Amount"],
				datasets: [{
					  	data: ['.$total_re_bal.', '.$total_paid_bal.'],
					 	 backgroundColor: ["#dc3545","#007bff"],
						}],
					},
				});
			</script>';
     }

    public function getDeductionDates($orig_deduction_date, $div)
    {
    	$date_sched = date('Y-m-d', strtotime($orig_deduction_date));
		$terms = $div;

		$cuttoff_date = $date_sched;		
		$ct_off1 = null;
		$ct_off2 = null;
		$get_day = Date('d', strtotime($cuttoff_date));

		if($get_day == '05'):
			$ct_off1 = '05';
			$ct_off2 = '20';
		elseif($get_day == '15'):
			$ct_off1 = '15';
			$ct_off2 = '30';
		elseif($get_day == '20'):
			$ct_off1 = '20';
			$ct_off2 = '05';
		elseif(($get_day == '30') || ($get_day == '31') || ($get_day == '28') || ($get_day == '29')):
			$ct_off1 = '30';
			$ct_off2 = '15';
			var_dump('asdasd');
		endif;

		$array_SCHEME = array();

		for($i=0; $i<$terms; $i++):
			$days = abs(15*$i);
			$ddate_partial = Date('Y-m-d', strtotime("+$days day", strtotime($cuttoff_date)));
			$date_echo = $ddate_partial;
			$mod = $i % 2;	
			if($mod == 0):
				if(date('j', strtotime($ddate_partial)) > $ct_off1):
					$ddef = abs(date('j', strtotime($ddate_partial)) - $ct_off1);
					$date_echo = Date('Y-m-d', strtotime("-$ddef day", strtotime($ddate_partial)));
				elseif(date('d', strtotime($ddate_partial)) < $ct_off1):
					$ddef = abs(date('j', strtotime($ddate_partial)) - $ct_off1);
					$date_echo = Date('Y-m-d', strtotime("+$ddef day", strtotime($ddate_partial)));
					if(($ct_off1 == '30') && (date('m', strtotime($ddate_partial)) == '02')):
						$date_echo = Date('Y-m-d', strtotime("last day of this month", strtotime($ddate_partial)));
					endif;
				else:
					$date_echo = $ddate_partial;
				endif;	
			else:
				if(date('j', strtotime($ddate_partial)) > $ct_off2):
					$ddef = abs(date('j', strtotime($ddate_partial)) - $ct_off2);
					$date_echo = Date('Y-m-d', strtotime("-$ddef day", strtotime($ddate_partial)));						
				elseif(date('d', strtotime($ddate_partial)) < $ct_off2):
					$ddef = abs(date('j', strtotime($ddate_partial)) - $ct_off2);
					$date_echo = Date('Y-m-d', strtotime("+$ddef day", strtotime($ddate_partial)));
					if(($ct_off2 == '30') && (date('m', strtotime($date_echo)) == '02')):
						$date_echo = Date('Y-m-d', strtotime("last day of this month", strtotime($ddate_partial)));
					endif;
				else:
					$date_echo = $ddate_partial;
				endif;		
			endif;
				$array_SCHEME[] = $date_echo;
		endfor;

		return $array_SCHEME;
    }

    public function getEmpLastDedDate($emp_id)
    {
    	$result = $this->db->query("select ded_deductiondate
    									from mhhp_deduction
    										where ded_id = (Select MAX(ded_id) From mhhp_deduction Where ded_emp = '$emp_id')");

    	// $result = $this->db->get();

    	return $result->row();
    }

    public function cut_off_day($cc,$bc)
    {
    	$this->db->select('*');
        $this->db->from('pis.cut_off as cut');
 		$this->db->where('cut.cc = '.$cc);
 		$this->db->where('cut.bc = '.$bc);
 		$this->db->limit(1);
        $query = $this->db->get();

        $result = $query->result_array();
        if(empty($result)):
        $this->db->select('*');
        $this->db->from('pis.cut_off as cut');
 		$this->db->where('cut.cc = "" ');
 		$this->db->where('cut.bc = "" ');
 		$this->db->limit(1);
        $query = $this->db->get();
    	endif;

    	return $query->result_array();

    }
    
    Public function get_extracted($emp_ID){
		$query  = $this->db->query("SELECT emp3.name,
					emp3.position,
					emp3.company_code,
					emp3.bunit_code,
					emp3.dept_code,
					emp3.section_code,
					emp3.sub_section_code,
					emp3.emp_id,
					emp3.emp_no,
					emp3.payroll_no,
					emp3.emp_type,
					emp3.current_status
					FROM pis.employee3 emp3
					WHERE (emp3.emp_id = '$emp_ID'
					OR emp3.emp_no = '$emp_ID'
					OR emp3.barcodeId = '$emp_ID')
					LIMIT 0, 1");
		return $query->result_array();
	}

	public function modal_header($title,$fa,$modal_size){
	    echo '<div class="modal-dialog '.$modal_size.' " role="document" >';
	    echo '<div class="modal-content" style="border-radius: 1.2rem!important;">';
	      echo '<div class="modal-header " >';
	            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
	              echo '<span aria-hidden="true">&times;</span>';
	        echo '</button>';
	      echo '<h5 class="modal-title" id="exampleModalLabel1" style="font-family: calibri;font-weight: 700;">'.$title.' <i class="'.$fa.'"></i></h4>';
	      echo '</div>';
	  }

	public function modal_body_open(){
	      echo '<div class="modal-body" >';
	}

	public function modal_body_close_footer_open(){
	      echo '</div>';
	      echo '<div class="modal-footer">';
	}

	public function modal_footer_close(){
	      echo '<div>';
	    echo'</div>';
	    echo'</div>';
	}


	public function get_amort($serial){
      $this->db->select('*');
			$this->db->from('ebs.mhhp_amortizationsched as amrt');
			$this->db->where('amrt.mhhp_serial_code', $serial);
			$query = $this->db->get();
      return $query->result_array();
	}

	public function mhhp_balance2ndcutoff($serial){
      $this->db->select('bal.mhhp_2nd_cutoff');
			$this->db->from('ebs.mhhp_balance as bal');
			$this->db->where('bal.mhhp_serial_code', $serial);
			$query = $this->db->get();
      return $query->result_array();
	}

	public function split_predate($schedule, $dateSELECT){
			$date_sched 				= date('d', strtotime($schedule));
			$dateselect_m 			= date('m', strtotime($dateSELECT));
			$date_ded 					= null;
			$date_schedINSERT 	= null;

			if(in_array($date_sched, array('28', '29', '30'))){
					$date_ded = date('Y-m-15', strtotime($dateSELECT));
					
					if($dateselect_m == '02'){
						$date_schedINSERT = date('Y-m-d', strtotime('last day of this month', strtotime($dateSELECT)));
					}else{
						$date_schedINSERT = date('Y-m-30', strtotime($dateSELECT));
					}
			}elseif(in_array($date_sched, array('05'))){
				
					$date_ded 				= date('Y-m-20', strtotime($dateSELECT));
					$date_schedINSERT = date('Y-m-05', strtotime('+1 month', strtotime($dateSELECT)));
			}elseif(in_array($date_sched, array('15'))){
				
					if($dateselect_m == '02'){
						$date_ded = date('Y-m-d', strtotime('last day of this month', strtotime($dateSELECT)));
					}else{
						$date_ded = date('Y-m-30', strtotime($dateSELECT));
					}
					
					$date_schedINSERT = date('Y-m-15', strtotime($dateSELECT));
			}elseif(in_array($date_sched, array('20'))){
				
					$date_ded 					= date('Y-m-05', strtotime($dateSELECT));
					$date_schedINSERT 	= date('Y-m-20', strtotime('-1 month', strtotime($dateSELECT)));
			}


			return array(
					'ded_date' => $date_ded,
					'ded_sched' => $date_schedINSERT
				);
		}

		public function partial_deduction($serial){
	      $this->db->select('*');
				$this->db->from('ebs.mhhp_partialdeduction as prt');
				$this->db->where('prt.mhhp_serial_code', $serial);
				$this->db->where('prt.prt_status', 'USED');
				$this->db->order_by("prt.prt_id",'asc');
				$this->db->limit(1);
				$query = $this->db->get();
	      return $query->result_array();
		}

		function prev_deductiondate($amrt_date, $slct_date){
				$date_amrt 		= date('Y-m-d', strtotime($amrt_date));
				$date_select 	= date('Y-m-d', strtotime($slct_date));	
				$date_final 	= null;
				

				if(strtotime($date_amrt) == strtotime($date_select)){
						$date_final = date('Y/m/d', strtotime($date_select));
				}elseif(strtotime($date_amrt) < strtotime($date_select)){
						
						if((in_array(date('d', strtotime($date_amrt)), array('30', '28', '29'))) && (in_array(date('d', strtotime($date_select)), array('30', '28', '29')))){
								
								if(date('m', strtotime($date_select)) === '02'){
										$date_final = date('Y/m/d', strtotime('last day of this month', strtotime($date_select)));			
								}else{
										$date_final = date('Y/m/30', strtotime($date_select));
								}

						}elseif(date('d', strtotime($date_amrt)) == date('d', strtotime($date_select))){
								
								$date_final = date('Y/m/'.date('d', strtotime($date_select)), strtotime($date_select));
						
						}elseif (in_array(date('d', strtotime($date_amrt)), array('05','20'))  && in_array(date('d', strtotime($date_select)), array('05','20'))){

								if(date('d', strtotime($date_amrt)) === '05'){

									$date_final = date('Y/m/05', strtotime($date_select));
								}else if(date('d', strtotime($date_amrt)) === '20'){

									$date_final = date('Y/m/20', strtotime($date_select));
								}
						}
				}
				return $date_final;
		}
		public function auto_splitdeduction($serial){
	      $this->db->select('*');
				$this->db->from('ebs.mhhp_splitautomatic spl');
				$this->db->where('spl.mhhp_serial_code', $serial);
				$this->db->where('spl.splt_status', 'ON');
				$this->db->order_by("spl.splt_id",'DESC');
				$this->db->limit(1);
				$query = $this->db->get();
	      return $query->result_array();
		}

		public function dropoff_amortization($serial, $date){
			$date_sched = date('Y-m-d', strtotime($date));
			$remain_amt = 0.00;

			$query  = $this->db->query("SELECT *
					      FROM ebs.mhhp_dropoff_deduction dp
					      WHERE dp.mhhp_serial_code = '$serial'
					      AND (dp.dp_schedule = '$date' OR dp.db_splitded_date = '$date')");

			if($query->num_rows() > 0){
				 	$row = $query->row_array();

					if(strtotime($row['db_splitded_date']) < strtotime($row['dp_schedule'])){
							if(($row['dp_schedule'] === $date_sched) && ($row['dp_status'] == 'PENDING')) {
									
									$remain_amt = $row['dp_fixedAMT'];
							}elseif(($row['db_splitded_date'] === $date_sched) && ($row['dp_status'] === 'PENDING')){
									
									$remain_amt = $row['db_splitAMT'];
							}else{
								
									$remain_amt = abs($row['dp_fixedAMT'] - $row['db_splitAMT']);
							}
					}else{
							if(($row['dp_schedule'] === $date_sched) && ($row['dp_status'] == 'PENDING')) {
									
									$remain_amt = $row['db_splitAMT'];
							}elseif(($row['db_splitded_date'] === $date_sched) && ($row['dp_status'] === 'PENDING')){
								
									$remain_amt = $row['dp_fixedAMT'];
							}else{
								
									$remain_amt = abs($row['dp_fixedAMT'] - $row['db_splitAMT']);
							}
					}	

					return array('DPDed_date' => $date_sched, 'DPamount' => $remain_amt);
			}else{
				
					return null;
			}
		}

		public function audited_list($serial,$date){
	      $this->db->select('*');
				$this->db->from('ebs.mhhp_audit aud');
				$this->db->where('aud.mhhp_serial_code', $serial);
				$this->db->where('aud.aud_deductiondate', $date);
				$query = $this->db->get();
	      return $query->result_array();
		}





}
