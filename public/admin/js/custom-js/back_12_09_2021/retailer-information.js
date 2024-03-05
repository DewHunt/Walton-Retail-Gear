jQuery(function(){
	//Modal Status Update Option 
	jQuery('.retailer-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var RetailerId = jQuery(this).data('id');
		var url = "retailerStatus"+"/"+RetailerId;
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

	// Get  Data AS MySql View Page   
	function getRetailerData() {
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		//var url = "retailer";
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

	// Add New Data
	jQuery('#AddRetailer').submit(function(e){
		e.preventDefault();
		jQuery('#name-error').html("");
		jQuery('#address-error').html("");
		jQuery('#owner-name-error').html("");
		jQuery('#phone-error').html("");

		jQuery.ajax({
			url:"retailer",
			method:"POST",
			data:new FormData(this),
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
			success:function(response) {

				if(response.errors) {
					if(response.errors.retailer_name){
						jQuery( '#name-error' ).html( response.errors.retailer_name[0] );
					}
					if(response.errors.retailder_address){
						jQuery( '#address-error' ).html( response.errors.retailder_address[0] );
					}
					if(response.errors.owner_name){
						jQuery( '#owner-name-error' ).html( response.errors.owner_name[0] );
					}
					if(response.errors.phone_number){
						jQuery( '#phone-error' ).html( response.errors.phone_number[0] );
					}
				}

				if(response == "success") {
					jQuery("#AddRetailer")[0].reset();
					Notiflix.Notify.Success( 'Data Insert Successfull' );
					return getRetailerData();
				}

				if(response.fail) {
					if(response.errors.name){
						jQuery("#AddRetailer")[0].reset();
						jQuery('#error_field').addClass('has-error');
						jQuery('#error-name').html( response.errors.name[0] );
						Notiflix.Notify.Failure( 'Data Insert Failed' );
					}
				}

			},
			error:function(error){
				jQuery("#AddRetailer")[0].reset();
				Notiflix.Notify.Failure( 'Data Insert Failed' );
			}
		});
	});

	// Edit  Data
	jQuery(document).on("click","#editInfo",function(e){
	  e.preventDefault();
	  var RetailId = jQuery(this).data('id');
	  var url = "retailer"+"/"+RetailId+"/edit";
	  jQuery.ajax({
			url:url,
			type:"GET",
			dataType:"JSON",
			beforeSend: function() {
    	        Notiflix.Loading.Arrows('Data Processing');
    	        Notiflix.Loading.Remove(300);
    	    },
			success:function(response){
				console.log(response);
				Notiflix.Loading.Remove(300);
				jQuery('#update_id').val(response.id);
				jQuery('.UpdateApiRetailerId').val(response.retailer_id).prop('readonly',true);
				jQuery('.UpdateApiRetailerName').val(response.retailer_name);
				jQuery('.UpdateApiRetailerAddress').val(response.retailder_address);
				jQuery('.UpdateApiRetailerOwnerName').val(response.owner_name);
				jQuery('.UpdateApiRetailerPhone').val(response.phone_number);
				jQuery('#UpdateApiRetailerPaymentType').val(response.payment_number_type);
				jQuery('#UpdateApiRetailerPaymentNumber').val(response.payment_number);
				jQuery('.UpdateApiRetailerZoneId').val(response.zone_id);
				jQuery('.UpdateApiRetailerDivisionId').val(response.division_id);
				jQuery('.UpdateApiRetailerDivisionName').val(response.division_name);
				jQuery('.UpdateApiRetailerDistricId').val(response.distric_id);
				jQuery('.UpdateApiRetailerDistric').val(response.distric_name);
				jQuery('.UpdateApiRetailerPoliceStation').val(response.police_station);
				jQuery('.UpdateApiRetailerThanaID').val(response.thana_id);
				jQuery('.UpdateApiRetailerDistributorCode').val(response.distributor_code);
				jQuery('.UpdateApiRetailerDistributorCode2').val(response.distributor_code2);
				jQuery('.UpdateApiCategoryId'+response.category_name).val(response.category_name).prop('selected',true);
				jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
				jQuery('.udealerAlternetCode').val(response.distributor_code2).prop('readonly',true);
				jQuery('.udealerName').val(response.dealer_name);
				jQuery('.udealerZone').val(response.zone_name);

				$("#udCode").css("display", "block");
				$("#udAlternetCode").css("display", "block");
				$("#udName").css("display", "block");
				$("#udZone").css("display", "block");

			if(response.payment_type == null) {
			    //alert(null);
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
			else if(response.payment_type == 1) {
			    //alert(1);
				jQuery("#umfc").prop("checked", true);
                jQuery('.bankDiv').hide();
                jQuery('.bank_field').remove();
                jQuery('.UpdateBankName').remove();
                
                jQuery('.agentDiv').show();
                $('.mfc_field').val(response.payment_number);
                $('.mfc_name').val(response.agent_name);
			} else {
			    //alert(2);
				jQuery("#ubank").prop("checked", true);
                jQuery('.agentDiv').hide();
                jQuery('.mfc_field').remove();
                jQuery('.mfc_name').remove();
                
                jQuery('.bankDiv').show();
                $('.bank_field').val(response.payment_number);
                $('.UpdateBankName').val(response.bank_name);
			}

				if (response.status == 1) {
					jQuery("#option1").prop("checked", true);
				} else {
			  	jQuery("#option2").prop("checked", true);
			  }

			}
	  });
	});

	// Update Data
	jQuery('#UpdateRetailer').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		var formData = new FormData(this);
		formData.append('_method', 'put');
	  
		var retailerId   	= jQuery('#update_id').val();
		var data     		= jQuery("#UpdateRetailer").serialize();
		
		jQuery.ajax({
			url:"retailer"+"/"+retailerId,
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
    	        Notiflix.Loading.Arrows('Data Processing');
    	        Notiflix.Loading.Remove(300);
    	    },
			success:function(response) {
				if(response.errors) {
					if(response.errors.retailer_name){
						jQuery( '#update-name-error' ).html( response.errors.retailer_name[0] );
					}
					if(response.errors.retailder_address){
						jQuery( '#update-address-error' ).html( response.errors.retailder_address[0] );
					}
					if(response.errors.owner_name){
						jQuery( '#update-owner-name-error' ).html( response.errors.owner_name[0] );
					}
					if(response.errors.phone_number){
						jQuery( '#update-phone-error' ).html( response.errors.phone_number[0] );
					}
				}
				if(response == "success"){
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getRetailerData();
				}
			
				if(response == "error"){
					Notiflix.Notify.Failure( 'Data Update Failed' );
				}
			
				if(response.fail) {
					if(response.errors.name){
						jQuery('#error_field').addClass('has-error');
						jQuery('#error-name').html( response.errors.name[0] );
						Notiflix.Notify.Failure( 'Data Update Failed' );
					}
				}
			},
			error:function(error) {
			  //jQuery("#UpdateRetailer")[0].reset();
			  Notiflix.Notify.Failure( 'Data Update Failed' );
			}
		});
	});

	//API Search Retailer By Id / Mobile
	jQuery(document).on("click","#search_retailer_button",function(e){
	  e.preventDefault();
	  var getRetailerId 		= jQuery('#search_retailer_id').val();
	  var getRetailerMobile 	= jQuery('#search_retailer_mobile').val();

	  RetailerId 		= "";
	  RetailerMobile 	= "";

	  if(getRetailerId !=''){
	  	RetailerId 		= getRetailerId;
	  	RetailerMobile 	= 0;
	  } else {
	  	RetailerMobile 	= getRetailerMobile;
	  	RetailerId 		= 0;
	  }

	  //var url = "apiretailer"+"/"+RetailerId+"/"+RetailerMobile;
	  var url = "apiretailer"+"/"+getRetailerMobile;
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(1000);
        },
		success:function(response){
			console.log(response);
			//if(response)
			if(!$.trim(response))
			{
				Notiflix.Notify.Failure('Data Not Found');
				Notiflix.Loading.Remove(600);
				jQuery('.ApiRetailerId').val(response.Id).prop('readonly',true);
				jQuery('.ApiRetailerName').val(response.RetailerName);
				jQuery('.ApiRetailerAddress').val(response.RetailerAddress);
				jQuery('.ApiRetailerOwnerName').val(response.OwnerName);
				jQuery('.ApiRetailerPhone').val(response.PhoneNumber);
				jQuery('.ApiRetailerPaymentType').val(response.PaymentNumberType);
				jQuery('.ApiRetailerPaymentNumber').val(response.PaymentNumber);
				jQuery('.ApiRetailerZoneId').val(response.ZoneId);
				jQuery('.ApiRetailerDivisionId').val(response.DivisionId);
				jQuery('.ApiRetailerDivisionName').val(response.Division);
				jQuery('.ApiRetailerDistricId').val(response.DistrictId);
				jQuery('.ApiRetailerDistric').val(response.District);
				jQuery('.ApiRetailerPoliceStation').val(response.PoliceStation);
				jQuery('.ApiRetailerThanaID').val(response.ThanaId);
				jQuery('.ApiRetailerDistributorCode').val(response.DistributorCode);
				jQuery('.ApiRetailerDistributorCode2').val(response.DistributorCode2);

				if(response == 'success') {
					jQuery("#AddRetailer")[0].reset();
					jQuery('#AddRetailerModal').modal('hide');
					Notiflix.Notify.Success('Data Insert Successfully');
				}  

			} else {
				//jQuery("#AddRetailer")[0].reset();
				//Notiflix.Notify.Failure( 'Data Not Found' );
				//Notiflix.Loading.Remove(600);

				Notiflix.Loading.Remove(600);
				jQuery('.ApiRetailerId').val(response.Id).prop('readonly',true);
				jQuery('.ApiRetailerName').val(response.RetailerName);
				jQuery('.ApiRetailerAddress').val(response.RetailerAddress);
				jQuery('.ApiRetailerOwnerName').val(response.OwnerName);
				jQuery('.ApiRetailerPhone').val(response.PhoneNumber);
				jQuery('.ApiRetailerPaymentType').val(response.PaymentNumberType);
				jQuery('.ApiRetailerPaymentNumber').val(response.PaymentNumber);
				jQuery('.ApiRetailerZoneId').val(response.ZoneId);
				jQuery('.ApiRetailerDivisionId').val(response.DivisionId);
				jQuery('.ApiRetailerDivisionName').val(response.Division);
				jQuery('.ApiRetailerDistricId').val(response.DistrictId);
				jQuery('.ApiRetailerDistric').val(response.District);
				jQuery('.ApiRetailerPoliceStation').val(response.PoliceStation);
				jQuery('.ApiRetailerThanaID').val(response.ThanaId);
				jQuery('.ApiRetailerDistributorCode').val(response.DistributorCode);
				jQuery('.ApiRetailerDistributorCode2').val(response.DistributorCode2);
			}

			if(response == 'empty' || response == 'error') {
				jQuery('.ApiRetailId').val(response.RetailId).prop('readonly',false);
				Notiflix.Notify.Failure( 'Data Not Found! Please Try Another Retailer Id..' );
				Notiflix.Loading.Remove(600);
			}
			
		}
	  });
	});
});

//Add All Retailer By Api Calling
function AddAllRetailerByApi() {
	var url = "apilistaddretailer/";
	jQuery.ajax({
	url:url,
	type:"GET",
	dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
		success:function(response){
			console.log(response);
			if(response) {
				if(response == 'success') {
					Notiflix.Loading.Remove(600);
					Notiflix.Notify.Success('Data Insert Successfully');
					window.location.reload();
					return getRetailerData();
				} else {
					Notiflix.Loading.Remove(600);
					Notiflix.Notify.Warning( 'Not Found! Please Try Another Zone Id..' );
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

function SearchRetailerDisable() {
	var RetailerId 		= jQuery('#search_retailer_id').val();

	if(RetailerId != '') {
		jQuery('#search_retailer_id').prop('disabled',false);
		jQuery('#search_retailer_mobile').prop('disabled',true);
	} else {
		jQuery('#search_retailer_id').prop('disabled',false);
		jQuery('#search_retailer_mobile').prop('disabled',false);
	}
}

function SearchRetailerMobileDisable() {
	var RetailerMobile 	= jQuery('#search_retailer_mobile').val();

	if(RetailerMobile != '') {
		jQuery('#search_retailer_mobile').prop('disabled',false);
		jQuery('#search_retailer_id').prop('disabled',true);
	} else {
		jQuery('#search_retailer_mobile').prop('disabled',false);
		jQuery('#search_retailer_id').prop('disabled',false);
	}
}

//API Search  By Dealer Code
jQuery(document).on("click","#search_retailer_dealer_button",function(e){
  e.preventDefault();
  var DealerCode = jQuery('#search_retailer_dealer_code').val();
  var url = "CheckDealerFromApi"+"/"+DealerCode;
  jQuery.ajax({
	url:url,
	type:"GET",
	dataType:"JSON",
	beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(300);
    },
	success:function(response) {

		console.log(response);
		if(!$.trim(response))
		{
			Notiflix.Notify.Warning('Invalid Distributor Code');
			Notiflix.Loading.Remove(600);
			jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
			jQuery('.dealerAlternetCode').val(response.ImportCode).prop('readonly',true);
			jQuery('.dealerName').val(response.DistributorNameCellCom);
			jQuery('.dealerZone').val(response.Zone);

			jQuery("#dCode").css("display", "block");
			jQuery("#dAlternetCode").css("display", "block");
			jQuery("#dName").css("display", "block");
			jQuery("#dZone").css("display", "block");

			/*setTimeout(function(){// wait for 5 secs(2)
				window.location.reload(); // then reload the page.(3)
				$(".btnCloseModal").click();
			}, 500);*/
		} 
		else 
		{
			
			Notiflix.Loading.Remove(600);
			jQuery('.dealerCode').val(response.DealerCode).prop('readonly',true);
			jQuery('.dealerAlternetCode').val(response.ImportCode).prop('readonly',true);
			jQuery('.dealerName').val(response.DistributorNameCellCom);
			jQuery('.dealerZone').val(response.Zone);

			jQuery("#dCode").css("display", "block");
			jQuery("#dAlternetCode").css("display", "block");
			jQuery("#dName").css("display", "block");
			jQuery("#dZone").css("display", "block");
		}
	}
  });
});

jQuery(document).on("click","#usearch_retailer_dealer_button",function(e){
  e.preventDefault();
  var DealerCode = jQuery('#usearch_retailer_dealer_code').val();
  var url = "CheckDealerFromApi"+"/"+DealerCode;
  jQuery.ajax({
	url:url,
	type:"GET",
	dataType:"JSON",
	beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(300);
    },
	success:function(response){
		console.log(response);
		if(response) {
			Notiflix.Loading.Remove(600);
			jQuery('.udealerCode').val(response.DealerCode).prop('readonly',true);
			jQuery('.udealerAlternetCode').val(response.ImportCode).prop('readonly',true);
			jQuery('.udealerName').val(response.DistributorNameCellCom);
			jQuery('.udealerZone').val(response.Zone);

			jQuery("#udCode").css("display", "block");
			jQuery("#udAlternetCode").css("display", "block");
			jQuery("#udName").css("display", "block");
			jQuery("#udZone").css("display", "block");
			
		} else {
			Notiflix.Notify.Failure( 'Data Not Found' );
			Notiflix.Loading.Remove(600);
		}
		
	}
  });
});