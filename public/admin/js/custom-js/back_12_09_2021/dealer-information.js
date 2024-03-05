jQuery(function(){
	//Dealer Information Modal Status Update Option 
	jQuery('.dealer-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var DealerId = jQuery(this).data('id');
		var url = "DealerStatus"+"/"+DealerId;
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
		
			success:function(response){
				if(response.success){
					Notiflix.Notify.Success('Data Update Successfull');
					Notiflix.Loading.Remove(600);
				}
				if(response.error){
					Notiflix.Notify.Failure('Data Update Failed');
					Notiflix.Loading.Remove(600);
				}
			}
		});
	});

	// Get Dealer Data AS MySql View Page   
	function getDealerData() {
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		//var url = "dealerinfo";
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

	// Add Dealer Data
	jQuery('#AddDealer').submit(function(e){
		e.preventDefault();
		jQuery('#dealer-code-error').html("");
		jQuery('#alternet-code-error').html("");
		jQuery('#name-error').html("");
		jQuery('#zone-error').html("");
		jQuery('#phone-error').html("");
		jQuery.ajax({
			url:"dealerinfo",
			method:"POST",
			data:new FormData(this),
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
	
			success:function(response) {
				if(response.errors) {
					if(response.errors.dealer_code){
						jQuery( '#dealer-code-error' ).html( response.errors.dealer_code[0] );
					}
					if(response.errors.alternate_code){
						jQuery( '#alternet-code-error' ).html( response.errors.alternate_code[0] );
					}
					if(response.errors.dealer_name){
						jQuery( '#name-error' ).html( response.errors.dealer_name[0] );
					}
					if(response.errors.zone){
						jQuery( '#zone-error' ).html( response.errors.zone[0] );
					}
					if(response.errors.dealer_phone_number){
						jQuery( '#phone-error' ).html( response.errors.dealer_phone_number[0] );
					}
				}
				if(response == "error") {
					Notiflix.Notify.Failure( 'Data Insert Failed' );
				}
				if(response == "success") {
					jQuery("#AddDealer")[0].reset();
					Notiflix.Notify.Success( 'Data Insert Successfull' );
					return getDealerData();
				}

			},
			error:function(error) {
				jQuery("#AddDealer")[0].reset();
				Notiflix.Notify.Failure( 'Data Insert Failed' );
			}
		});
	});

	// Edit Dealer Data
	jQuery(document).on("click","#editDealerInfo",function(e){
	  e.preventDefault();
	  var DealerId = jQuery(this).data('id');
	  var url = "dealerinfo"+"/"+DealerId+"/edit";
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",

		success:function(response){
			if(response.dealer_id){
				jQuery('.dealerid').val(response.dealer_id).prop('readonly',true);
			} else{
				jQuery('.dealerid').val(response.dealer_id);
			}
			
			if(response.dealer_code && response.alternate_code){
				jQuery('.dealercode').val(response.dealer_code).prop('readonly',true);
				jQuery('.alternetcode').val(response.alternate_code).prop('readonly',true);
			}else if(response.alternate_code) {
				jQuery('.dealercode').val(response.dealer_code);
				jQuery('.alternetcode').val(response.alternate_code).prop('readonly',true);
			}else if(response.dealer_code) {
				jQuery('.dealercode').val(response.dealer_code).prop('readonly',true);
				jQuery('.alternetcode').val(response.alternate_code);
			} else {
				jQuery('.dealercode').val(response.dealer_code);
				jQuery('.alternetcode').val(response.alternate_code);
			}

			//jQuery('.dealercode').val(response.dealer_code).prop('readonly',true);
			//jQuery('.alternetcode').val(response.alternate_code);
			jQuery('.dealername').val(response.dealer_name);
			jQuery('.zone').val(response.zone);
			jQuery('.city').val(response.city);
			jQuery('.division').val(response.division);
			jQuery('.dealerphone').val(response.dealer_phone_number);
			jQuery('.dealertype').val(response.dealer_type);
			jQuery('.dealeraddress').val(response.dealer_address);
		}
	  });
	});

	// Update Dealer Data
	jQuery('#UpdateDealerOld').on("submit", function(arg){
        jQuery.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        arg.preventDefault();
        jQuery('#update-dealer-code-error').html("");
		jQuery('#update-alternet-code-error').html("");
		jQuery('#update-name-error').html("");
		jQuery('#update-zone-error').html("");
		jQuery('#update-phone-error').html("");


        var formData = new FormData(this);
        formData.append('_method', 'put');
      
        var dealerId 	= jQuery('#dealer_id').val();
        var data    	= jQuery("#UpdateDealer").serialize();
        
        jQuery.ajax({
            url:"dealerinfo"+"/"+dealerId,
            type:"POST",
            data:formData,
            dataType:'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success:function(response) {
            	if(response.errors) {
					if(response.errors.dealer_code){
						jQuery( '#update-dealer-code-error' ).html( response.errors.dealer_code[0] );
					}
					if(response.errors.alternate_code){
						jQuery( '#update-alternet-code-error' ).html( response.errors.alternate_code[0] );
					}
					if(response.errors.dealer_name){
						jQuery( '#update-name-error' ).html( response.errors.dealer_name[0] );
					}
					if(response.errors.zone){
						jQuery( '#update-zone-error' ).html( response.errors.zone[0] );
					}
					if(response.errors.dealer_phone_number){
						jQuery( '#update-phone-error' ).html( response.errors.dealer_phone_number[0] );
					}
				}

                if(response == "success"){
                    Notiflix.Notify.Success( 'Data Update Successfull' );
                    return getDealerData();
                }
                if(response == "error"){
                    Notiflix.Notify.Failure( 'Data Update Failed' );
                    //console.log(response);
                }
                
            }
        });
    });
    
    // Updat Dealer Data New
	jQuery('#UpdateDealer').submit(function(e){
		e.preventDefault();
		jQuery('#dealer-code-error').html("");
		jQuery('#alternet-code-error').html("");
		jQuery('#name-error').html("");
		jQuery('#zone-error').html("");
		jQuery('#phone-error').html("");

		jQuery.ajax({
			url:"dealerinfo",
			method:"POST",
			data:new FormData(this),
			dataType:'JSON',
			contentType: false,
			cache: false,
			processData: false,
	
			success:function(response) {
				if(response.errors) {
					if(response.errors.dealer_code){
						jQuery( '#dealer-code-error' ).html( response.errors.dealer_code[0] );
					}
					if(response.errors.alternate_code){
						jQuery( '#alternet-code-error' ).html( response.errors.alternate_code[0] );
					}
					if(response.errors.dealer_name){
						jQuery( '#name-error' ).html( response.errors.dealer_name[0] );
					}
					if(response.errors.zone){
						jQuery( '#zone-error' ).html( response.errors.zone[0] );
					}
					if(response.errors.dealer_phone_number){
						jQuery( '#phone-error' ).html( response.errors.dealer_phone_number[0] );
					}
				}
				if(response == "error") {
					Notiflix.Notify.Failure( 'Data Update Failed' );
				}
				if(response == "success") {
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getDealerData();
				}
			},
			error:function(error) {
				Notiflix.Notify.Failure( 'Data Update Failed' );
			}
		});
	});

    //API Search  By Dealer Code
	jQuery(document).on("click","#search_dealer_button",function(e){
	  e.preventDefault();
	  var DealerCode = jQuery('#search_dealer_code').val();
	  var url = "CheckDealerFromApi"+"/"+DealerCode;
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(400);
        },
		success:function(response){
			console.log(response);
			if(response) {
				Notiflix.Loading.Remove(600);
				jQuery('.apidealerid').val(response.Id);
				jQuery('.apidcode').val(response.DealerCode).prop('readonly',true);
				jQuery('.apialtercode').val(response.ImportCode);
				jQuery('.apidname').val(response.DistributorNameCellCom);
				jQuery('.apidzone').val(response.Zone);
				jQuery('.apidphone').val(response.MobileNo);
				jQuery('.apidaddress').val(response.Address);

				if(response == 'success') {
					jQuery("#AddDealer")[0].reset();
					jQuery('#AddDealerModal').modal('hide');
					Notiflix.Notify.Success('Data Insert Successfully');
				}  

			} 
			else {
				jQuery("#AddDealer")[0].reset();
				Notiflix.Notify.Failure( 'Data Not Found' );
				Notiflix.Loading.Remove(600);
			}

			if(response == 'empty' || response == 'error') {
				jQuery('.apidealerid').val(response.Id).prop('readonly',false);
				Notiflix.Notify.Info( 'Data Not Found! Please Try Another Dealer Id..' );
				Notiflix.Loading.Remove(600);
			}
			
		}
	  });
	});
});


//Add All Dealer By Api Calling
function ClickAddToDealerFormApi() {
	var url = "AddToDealerFormApi/";
	jQuery.ajax({
	url:url,
	type:"GET",
	dataType:"JSON",
		success:function(response){
			console.log(response);
			if(response) {
				if(response == 'success') {
					Notiflix.Notify.Success('Data Insert Successfully');
					window.location.reload();
					return getRetailerData();
				} else {
					Notiflix.Notify.Warning( 'Not Found! Please Try Another Zone Id..' );
				}
			} else {
				Notiflix.Notify.Failure( 'Data Not Found' );
			}
			
		},
	   complete:function(response){
	    // Hide image container
	    Notiflix.Loading.Remove(600);
	   }
	});
}