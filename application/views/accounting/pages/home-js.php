<script>

	$(document).ready(function(){		
		$('#dataTable').DataTable();


		 $.ajax({
	                    url:'<?php echo base_url() ?>accounting/mh_chart_r',
	                    method:'POST',
	                    cache: false,
	                    
	                    success:function(data)
	                    {
	                    
	                      $('.chart_mh').html(data);
	                    }
	               });
	})

	$('.action').on('click',function(){
	      $('#modal').modal();
	      var id = $(this).attr('id');
	      $.ajax({
	                    url:'<?php echo base_url() ?>accounting/modal_employeee_monitoring_r',
	                    method:'POST',
	                    cache: false,
	                    data:{
	                      post_id:id
	                    },
	                    success:function(data)
	                    {
	                      $('.modal_content').html(data);
	                    }
	               }); 
	});



	$('.unaudited_summary_mhhp').on('click',function(){
		$('#modal').modal();
		$.ajax({
		                   url:'<?php echo base_url() ?>accounting/modal_unaudited_r',
		                   method:'POST',
		                   cache: false,
		                   // data:{
		                   //   post_id:id
		                   // },
		                   success:function(data)
		                   {
		              	  		$('.modal_content').html(data);
		                   }
		});
	});

	$('.unaudited_summary_habitat').on('click',function(){
		$('#modal').modal();
		$.ajax({

			url:'<?php echo base_url() ?>accounting/modal_unaudited_habitat_r',
		                   method:'POST',
		                   cache: false,
		                   // data:{
		                   //   post_id:id
		                   // },
		                   success:function(data)
		                   {
		              	  		$('.modal_content').html(data);
		                   }

		});

	});

	$('.hb_action').on('click',function(){
		var id = $(this).attr('id');
 		// alert(post_id);
 		$('#modal').modal();
 		$.ajax({
            url:'<?php echo base_url() ?>accounting/hb_modal_employeee_monitoring_r',
            method:'POST',
            cache: false,
            data:{
              post_id:id
            },
            success:function(data)
            { 
              $('.modal_content').html(data);
            }
	    });

	});


	$("#modal").on("hidden.bs.modal", function(){
	    $(".modal_content").html("");
	});

	
	$('.wkinpay').on('click',function(){
		var id = $(this).attr('id');
		$('#modal').modal();
		$.ajax({
	            url:'<?php echo base_url() ?>accounting/mh_walkinpay_r',
	            method:'POST',
	            cache: false,
	            data:{
	              post_id:id
	            },
	            success:function(data)
	            { 
	              $('.modal_content').html(data);
	            }
	    });
	});


	$('.hb_wkinpay').on('click',function(){
		var id = $(this).attr('id');
		$('#modal').modal();
		$.ajax({
	            url:'<?php echo base_url() ?>accounting/hb_walkinpay_r',
	            method:'POST',
	            cache: false,
	            data:{
	              post_id:id
	            },
	            success:function(data)
	            { 
	              $('.modal_content').html(data);
	            }
	    });

	});

</script>