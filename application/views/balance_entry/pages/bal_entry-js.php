<script>

	$(document).ready(function(){		
		$('#dataTable').DataTable();
	})




	$('.save_data').on('click',function(){
		var Employee_ID 	= $('#Employee_ID').val();
		var Request_Number 	= $('#Request_Number').val();
		var Devt_Cost 		= $('#Devt_Cost').val();
		var Bldg_Cost 		= $('#Bldg_Cost').val();
		var Balance_Amount 	= $('#Balance_Amount').val();
		var st1_Deduction 	= $('#1st_Deduction').val();
		var st2_Deduction 	= $('#2st_Deduction').val();


		if(Employee_ID ==''){
			alertify.warning("Empty fields");
		}else{
			$.ajax({
               url:'<?php echo base_url() ?>balance_entry/insert_emp_mh_r',
               method:'POST',
               cache: false,
               data:{
					p_Employee_ID:Employee_ID,
					p_Request_Number:Request_Number,
					p_Devt_Cost:Devt_Cost,
					p_Bldg_Cost:Bldg_Cost,
					p_Balance_Amount:Balance_Amount,
					p_st1_Deduction:st1_Deduction,
					p_st2_Deduction:st2_Deduction
               },
               success:function(data)
               {
          	  		alertify.success("Successfuly Added");
          	  		$('#Devt_Cost').val("");
					$('#Bldg_Cost').val("");
          	  		$('#1st_Deduction').val("");
					$('#2st_Deduction').val("");
					$('#Employee_Name').focus();
               }
		});
	  }
	});


	$('#Employee_Name_mh').on('keyup',function(){

		var emp_name = $(this).val();
		$('.search_result').show();
		$.ajax({
		                   url:'<?php echo base_url() ?>balance_entry/search_emp_mh_r',
		                   method:'POST',
		                   cache: false,
		                   data:{
		                     post_emp_name:emp_name
		                   },
		                   success:function(data)
		                   {
		              	  		$('.search_result').html(data);
		                   		// alert(data);
		                   }
		});

	});

	
	$('#Employee_Name_hb').on('keyup',function(){

		var emp_name = $(this).val();

		$('.search_hb_result').show();
		$.ajax({
		                   url:'<?php echo base_url() ?>balance_entry/search_emp_hb_r',
		                   method:'POST',
		                   cache: false,
		                   data:{
		                     post_emp_name:emp_name
		                   },
		                   success:function(data)
		                   {
		              	  		$('.search_hb_result').html(data);
		                   		// alert(data);
		                   }
		});

	});

	
	$('.save_data_hb').on('click',function(){
		var emp_id = $('#Employee_ID_hb').val();
		var req_no = $('#Request_Number_hb').val();
		var bal_amt = $('#Balance_Amount_hb').val();
		var ded_amt = $('#Deduction_amt_hb').val();

		if(emp_id ==''){
			alertify.warning("Empty fields");
		}

		else{
		$.ajax({
               url:'<?php echo base_url() ?>balance_entry/insert_emp_hb_r',
               method:'POST',
               cache: false,
               data:{
					p_emp_id:emp_id,
					p_req_no:req_no,
					p_bal_amt:bal_amt,
					p_ded_amt:ded_amt
               },
               success:function(data)
               {
          	  		alertify.success("Successfuly Added");
          	  		$('#Employee_ID_hb').val("");
					$('#Request_Number_hb').val("");
          	  		$('#Balance_Amount_hb').val("");
					$('#Deduction_amt_hb').val("");
					$('#Employee_Name_hb').focus();
               }
		});
	   }

	});

</script>