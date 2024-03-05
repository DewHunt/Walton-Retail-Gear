jQuery(function(){
	//Brand Promoter Information Modal Status Update Option 
	jQuery('.promoter-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var PromoterId = jQuery(this).data('id');
		var url = "BPromoterStatus"+"/"+PromoterId;
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response) {
				if(response.success) {
					Notiflix.Notify.Success( 'Data Update Successfull' );
				}
			
				if(response.error) {
					Notiflix.Notify.Failure( 'Data Update Failed' );
				}
			}
		});
	});

	// Get  Data AS MySql View Page   
	function getPromoterData(){
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		//var url = "bpromoter";
		jQuery.ajax({
		//url:url,
		url:"?page="+page+"&sortby="+column_name+"&sorttype="+sort_type+"&query="+query,
		type:"GET",
		dataType:"HTMl",
			success:function(response) {
				jQuery('.loading').hide();
				setTimeout(function(){// wait for 5 secs(2)
				window.location.reload(); // then reload the page.(3)
				}, 500);
			},
		});
	}

	// Add New Data
	jQuery('#AddBPromoter').submit(function(e){
	  e.preventDefault();
	  jQuery('#name-error').html("");
	  jQuery('#phone-error').html("");
	  jQuery('#search_bpromoter_phone').html("");
	  jQuery.ajax({
		url:"bpromoter",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,
		success:function(response) {
			console.log(response);	
			if(response.errors) {
				if(response.errors.bp_name){
					jQuery( '#name-error' ).html( response.errors.bp_name[0] );
				}
				if(response.errors.bp_phone){
					jQuery( '#phone-error' ).html( response.errors.bp_phone[0] );
				}
			}
			if(response == "success") {
				jQuery("#AddBPromoter")[0].reset();
				Notiflix.Notify.Success( 'Data Insert Successfull' );
				return getPromoterData();
			}
			if(response.fail) {
				if(response.errors.name) {
					jQuery("#AddBPromoter")[0].reset();
					jQuery('#error_field').addClass('has-error');
					jQuery('#error-name').html( response.errors.name[0] );
					Notiflix.Notify.Failure( 'Data Insert Failed' );
					setTimeout(function(){// wait for 5 secs(2)
						window.location.reload(); // then reload the page.(3)
						$(".btnCloseModal").click();
					}, 2000);
				}
			}
		},
		error:function(error) {
			jQuery("#AddBPromoter")[0].reset();
			Notiflix.Notify.Failure( 'Data Insert Failed' );
			setTimeout(function(){// wait for 5 secs(2)
			window.location.reload(); // then reload the page.(3)
				$(".btnCloseModal").click();
			}, 2000);
		}
	  });
	});

	// Edit  Data
	jQuery(document).on("click","#editBPromoterInfo",function(e){
	  e.preventDefault();
	  var PromoterId = jQuery(this).data('id');
	  var url = "bpromoter"+"/"+PromoterId+"/edit";
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(200);
        },
		success:function(response){
			console.log(response);
			Notiflix.Loading.Remove(300);			
			jQuery('#update_id').val(response.id);
			jQuery('.UpdateApiBPromoterId').val(response.bp_id);
			jQuery('.UpdateApiBPromoterRetailerId').val(response.retailer_id);
			jQuery('.UpdateApiBPromoterName').val(response.bp_name);
			jQuery('.UpdateApiBPromoterPhone').val(response.bp_phone);
			jQuery('.UpdateApiRetailerId').val(response.retailer_id).prop('readonly',true);
			jQuery('.UpdateApiRetailerName').val(response.retailer_name);
			jQuery('.UpdateApiRetailerAddress').val(response.retailder_address);
			jQuery('.UpdateApiRetailerOwnerName').val(response.owner_name);
			jQuery('.UpdateApiRetailerPhone').val(response.retailer_phone_number);
			jQuery('#UpdateApiRetailerPaymentType').val(response.payment_number_type);
			jQuery('#UpdateApiRetailerPaymentNumber').val(response.payment_number);
			//jQuery('.UpdateApiRetailerZoneId').val(response.zone_id);
			jQuery('.UpdateApiRetailerDivisionId').val(response.division_id);
			jQuery('.UpdateApiRetailerDivisionName').val(response.division_name);
			jQuery('.UpdateApiRetailerDistricId').val(response.distric_id);
			jQuery('.UpdateApiRetailerDistric').val(response.distric_name);
			jQuery('.UpdateApiRetailerPoliceStation').val(response.police_station);
			//jQuery('.UpdateApiRetailerThanaID').val(response.thana_id);
			jQuery('.UpdateApiRetailerDistributorCode').val(response.distributor_code);
			jQuery('.UpdateApiRetailerDistributorCode2').val(response.distributor_code2);
			jQuery('.UpdateApiCategoryId'+response.category_id).val(response.category_id).prop('selected',true);

			//////////////////////////////////////
			jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
			jQuery('.udealerAlternetCode').val(response.distributor_code2).prop('readonly',true);
			jQuery('.udealerName').val(response.distributor_name);
			jQuery('.udealerZone').val(response.distributor_zone);

			$("#udCode").css("display", "block");
			$("#udAlternetCode").css("display", "block");
			$("#udName").css("display", "block");
			$("#udZone").css("display", "block");

			if(response.payment_type == null) {
				jQuery("#umfc").prop("checked", false);
				jQuery("#ubank").prop("checked", false);
				jQuery('.agentDiv').hide();
				jQuery('.bankDiv').hide();

				jQuery('.mfc_field').val();
                jQuery('.mfc_name').val();
                jQuery('.bank_field').val();
                jQuery('.UpdateBankName').val();

				jQuery('.mfc_field').hide();
                jQuery('.mfc_name').hide();
                jQuery('.bank_field').hide();
                jQuery('.UpdateBankName').hide();
			}
			else if (response.payment_type == 1) {
				jQuery("#umfc").prop("checked", true);
                jQuery('.agentDiv').show();
                jQuery('.bankDiv').hide();
                jQuery('.mfc_field').val(response.payment_number);
                jQuery('.mfc_name').val(response.agent_name);
                $('.bank_field').remove();
                $('.UpdateBankName').remove();
			} else {
				jQuery("#ubank").prop("checked", true);
                jQuery('.agentDiv').hide();
                jQuery('.bankDiv').show();
                $('.mfc_field').remove();
                $('.mfc_name').remove();
                jQuery('.bank_field').val(response.payment_number);
                jQuery('.UpdateBankName').val(response.bank_name);
			}
			////////////////////////////////////////


			if (response.status == 1) {
				jQuery("#option1").prop("checked", true);
			} else {
		  		jQuery("#option2").prop("checked", true);
		  	}


		  	if(!(response.distributor_code) && !(response.distributor_code2))
			{
				Notiflix.Notify.Warning('Sorry You Are Not Update.Because Distributor Code Not Found');
				/*setTimeout(function(){// wait for 5 secs(2)
					window.location.reload(); // then reload the page.(3)
					$(".btnCloseModal").click();
				}, 2000);*/
			}
		}
	  });
	});

	// Update Data
	jQuery('#UpdateBPromoter').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		var formData = new FormData(this);
		formData.append('_method', 'put');
	  
		var promoterId   = jQuery('#update_id').val();
		var data         = jQuery("#UpdateBPromoter").serialize();
		jQuery('#update-name-error').html("");
	  	jQuery('#update-phone-error').html("");
		
		jQuery.ajax({
			url:"bpromoter"+"/"+promoterId,
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){
				if(response == "success") {
	                //jQuery("#editBPromoterModal").hide();
					jQuery("#UpdateBPromoter")[0].reset();
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getPromoterData();
				}
				if(response.errors) {
					if(response.errors.bp_name) {
						jQuery( '#update-name-error' ).html( response.errors.bp_name[0] );
					}
					if(response.errors.bp_phone){
						jQuery( '#update-phone-error' ).html( response.errors.bp_phone[0] );
					}
				}
				if(response.fail) {
					if(response.errors.name) {
						jQuery('#error_field').addClass('has-error');
						jQuery('#error-name').html( response.errors.name[0] );
						Notiflix.Notify.Failure( 'Data Update Failed' );
					}
				}
				if(response=="error"){
					jQuery("#UpdateBPromoter")[0].reset();
					Notiflix.Notify.Failure( 'Data Update Failed' );
				}
			},
			error:function(error) {
			  jQuery("#UpdateBPromoter")[0].reset();
			  Notiflix.Notify.Failure( 'Data Update Failed' );
			}
		});
	});

	//API Search Promoter By Id
	jQuery(document).on("click","#search_bpromoter_button",function(e){
	  e.preventDefault();
	  var PromoterPhone = jQuery('#search_bpromoter_phone').val();
	  var url = "CheckBPromoterFromApi"+"/"+PromoterPhone;
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(200);
        },
		success:function(response){
			console.log(response);
			if(response)
			{
				Notiflix.Loading.Remove(600);
				jQuery('.ApiBPromoterId').val(response.Id).prop('readonly',true);
				jQuery('.ApiBPromoterRetailerId').val(response.bpRetailId);
				jQuery('.ApiBPromoterName').val(response.bpName);
				jQuery('.ApiBPromoterPhone').val(response.bpPhone);
				if(response == 'success')
				{
					jQuery("#AddBPromoter")[0].reset();
					jQuery('#AddBPromoterModal').modal('hide');
					Notiflix.Notify.Success('Data Insert Successfully');
				}  

			}
			else
			{
				Notiflix.Notify.Failure( 'Data Not Found' );
			}

			if(response == 'empty' || response == 'error')
			{
				jQuery('.ApiBPromoterId').val(response.Id).prop('readonly',false);
				 Notiflix.Notify.Info( 'Data Not Found! Please Try Another Retailer Id..' );
			}
			
		}
	  });
	});
});

//Add All Brand Promoter By Api Calling
function AddAllPromoterByApi() {
	var url = "AddBPromoterFromApi/";
	jQuery.ajax({
	url:url,
	type:"GET",
	dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(200);
        },
		success:function(response){
			console.log(response);
			if(response) {
				if(response == 'success') {
					Notiflix.Loading.Remove(600);
					Notiflix.Notify.Success('Data Insert Successfully');
					window.location.reload();
					return getPromoterData();
				} else {
					Notiflix.Loading.Remove(600);
					Notiflix.Notify.Warning( 'Data Not Found! Please Try Another Id..' );
				}
			} else {
				Notiflix.Loading.Remove(600);
				Notiflix.Notify.Failure( 'Data Not Found' );
			}
			
		},
	   complete:function(response){
	    // Hide image container
	    Notiflix.Loading.Remove(600);
	   }
	});
}