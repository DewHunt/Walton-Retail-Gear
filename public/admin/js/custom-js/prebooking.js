jQuery(function(){
	//Product Information Modal Status Update Option 
	jQuery('.pre-booking-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var getId = jQuery(this).data('id');
		var url = "prebooking.status"+"/"+getId;
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){
				if(response.success){
					Notiflix.Notify.Success( 'Data Update Successfull' );
				}
			
				if(response.error){
					Notiflix.Notify.Failure( 'Data Update Failed' );
				}

			}
		});
		
	});

	// Get Product Data AS MySql View Page   
	function getPreBookingData() {
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		jQuery.ajax({
			url:"?page="+page+"&sortby="+column_name+"&sorttype="+sort_type+"&query="+query,
			type:"GET",
			dataType:"HTMl",
			success:function(response){
				jQuery('.loading').hide();
				setTimeout(function(){// wait for 5 secs(2)
				window.location.reload(); // then reload the page.(3)
				}, 500);
			},
		});
	}

	// Add Product Data
	jQuery('#AddProductPreBooking').submit(function(e) {
	  e.preventDefault();
	  jQuery('#model-error').html("");
	  jQuery('#color-error').html("");
	  jQuery('#start-date-error').html("");
	  jQuery('#end-date-error').html("");
	  jQuery('#minimum-advance-amount-error').html("");
	  jQuery('#maximum-qty-error').html("");
	  jQuery('#price-error').html("");
	  jQuery.ajax({
		url:"prebooking.add",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function() {
	        Notiflix.Loading.Arrows('Data Processing');
	    },
		success:function(response)	{
			Notiflix.Loading.Remove(300);
			if(response.errors) {
				if(response.errors.model){
					jQuery( '#model-error' ).html( response.errors.model[0] );
				}
				if(response.errors.color){
					jQuery( '#color-error' ).html( response.errors.color[0] );
				}
				if(response.errors.start_date){
					jQuery( '#start-date-error' ).html( response.errors.start_date[0] );
				}
				if(response.errors.end_date){
					jQuery( '#end-date-error' ).html( response.errors.end_date[0] );
				}
				if(response.errors.minimum_advance_amount){
					jQuery( '#minimum-advance-amount-error' ).html( response.errors.minimum_advance_amount[0] );
				}
				if(response.errors.max_qty){
					jQuery( '#maximum-qty-error' ).html( response.errors.max_qty[0] );
				}
				if(response.errors.price){
					jQuery( '#price-error' ).html( response.errors.price[0] );
				}
			}

			if(response == "error") {
				Notiflix.Notify.Failure('Data Insert Failed');
			}

			if(response == "success") {
				jQuery("#AddProductPreBooking")[0].reset();
				jQuery(".btnCloseModal").click();
				Notiflix.Notify.Success('Data Insert Successfull');
				return getPreBookingData();
			}
		},
		error:function(error)	{
			Notiflix.Notify.Failure('Data Insert Failed');
		}
	  });
	});

	// Edit Product Data
	jQuery(document).on("click","#editPreBookingInfo",function(e){
		e.preventDefault();
		var getId 	= jQuery(this).data('id');
		var url 	= "prebooking.edit"+"/"+getId;
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:"JSON",
			success:function(response)	{
				//console.log(response);
				jQuery('#updateId').val(response.id);
				jQuery('.getModel').val(response.model);
				jQuery('.getColor').val(response.color);
				jQuery('.getSdate').val(response.start_date);
				jQuery('.getEdate').val(response.end_date);
				jQuery('.getMiniMumAmount').val(response.minimum_advance_amount);
				jQuery('.getMaxQty').val(response.max_qty);
				jQuery('.getPrice').val(response.price);
				if (response.status == 1){
					jQuery("#option1").prop("checked", true);
				} else {
			  		jQuery("#option2").prop("checked", true);
			  	}
			}
		});
	});

	// Update Product Data
	jQuery('#UpdateProductPreBooking').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		jQuery('#update-model-error').html("");
		jQuery('#update-color-error').html("");
		jQuery('#update-start-date-error').html("");
		jQuery('#update-end-date-error').html("");
		jQuery('#update-minimum-advance-amount-error').html("");
		jQuery('#update-maximum-qty-error').html("");
		jQuery('#update-price-error').html("");

		var formData = new FormData(this);
		formData.append('_method', 'post');
	  
		var getId   = jQuery('#updateId').val();
		var data    = jQuery("#UpdateProductPreBooking").serialize();
		
		jQuery.ajax({
			url:"prebooking.update",
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
		        Notiflix.Loading.Arrows('Data Processing');
		    },
			success:function(response)	{
				Notiflix.Loading.Remove(300);
				if(response.errors) {
					if(response.errors.model){
						jQuery( '#update-model-error' ).html( response.errors.model[0] );
					}
					if(response.errors.color){
						jQuery( '#update-color-error' ).html( response.errors.color[0] );
					}
					if(response.errors.start_date){
						jQuery( '#update-start-date-error' ).html( response.errors.start_date[0] );
					}
					if(response.errors.end_date){
						jQuery( '#update-end-date-error' ).html( response.errors.end_date[0] );
					}
					if(response.errors.minimum_advance_amount){
						jQuery( '#update-minimum-advance-amount-error' ).html( response.errors.minimum_advance_amount[0] );
					}
					if(response.errors.max_qty){
						jQuery( '#update-maximum-qty-error' ).html( response.errors.max_qty[0] );
					}
					if(response.errors.price){
						jQuery( '#update-price-error' ).html( response.errors.price[0] );
					}
				}

				if(response == "success")	{
					jQuery(".btnCloseModal").click();
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getPreBookingData();
					console.log(response);
					Notiflix.Loading.Remove(600);
				}
			
				if(response == "error")	{
					Notiflix.Notify.Failure( 'Data Update Failed' );
					console.log(response);
				}
			}
		});
	});

});

