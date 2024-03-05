jQuery(function(){
	//Employee Information Modal Status Update Option 
	jQuery('.zone-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var ZoneId = jQuery(this).data('id');
		var url = "zoneStatus"+"/"+ZoneId;
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
	function getZoneData(){
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		//var url = "zone";
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
	jQuery('#AddZone').submit(function(e){
	  e.preventDefault();
	  jQuery('#name-error').html("");
	  jQuery.ajax({
		url:"zone",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,

		success:function(response) {
			if(response.errors) {
				if(response.errors.zone_name){
					jQuery( '#name-error' ).html( response.errors.zone_name[0] );
				}
			}
			if(response == "success"){
				jQuery("#AddZone")[0].reset();
				Notiflix.Notify.Success( 'Data Insert Successfull' );
				return getZoneData();
			}

		},
		error:function(error){
		  jQuery("#AddZone")[0].reset();
		  Notiflix.Notify.Failure( 'Data Insert Failed' );
		}
	  });
	});

	// Edit  Data
	jQuery(document).on("click","#editZoneInfo",function(e){
	  e.preventDefault();
	  var ZoneId = jQuery(this).data('id');
	  var url = "zone"+"/"+ZoneId+"/edit";
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
		success:function(response){
			console.log(response);
			Notiflix.Loading.Remove(300);
			jQuery('#update_id').val(response.id);
			jQuery('.UpdateApiZoneId').val(response.zone_id);
			jQuery('.UpdateApiZoneName').val(response.zone_name);

			if (response.status == 1){
				jQuery("#option1").prop("checked", true);
			} else {
		  		jQuery("#option2").prop("checked", true);
		  	}
		}
	  });
	});

	// Update Data
	jQuery('#UpdateZone').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		var formData = new FormData(this);
		formData.append('_method', 'put');
	  
		var zoneId   = jQuery('#update_id').val();
		var data     = jQuery("#UpdateZone").serialize();
		
		jQuery.ajax({
			url:"zone"+"/"+zoneId,
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){
				if(response == "success"){
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getZoneData();
				}
			
				if(response == "error"){
					Notiflix.Notify.Failure( 'Data Update Failed' );
				}
			},
			error:function(error) {
			  jQuery("#UpdateZone")[0].reset();
			  Notiflix.Notify.Failure( 'Data Update Failed' );
			  
			}
		});
	});

	//API Search ZONE By Id
	jQuery(document).on("click","#search_zone_button",function(e){
	  e.preventDefault();
	  var ZoneId = jQuery('#search_zone_id').val();
	  var url = "apizone"+"/"+ZoneId;
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
				Notiflix.Loading.Remove(600);
				jQuery('.ApiZoneId').val(response.Id).prop('readonly',true);
				jQuery('.ApiZoneName').val(response.ZoneName);
				if(response == 'success') {
					jQuery("#AddZone")[0].reset();
					jQuery('#AddZoneModal').modal('hide');
					Notiflix.Notify.Success('Data Insert Successfully');
				}  

			} else {
				Notiflix.Notify.Failure( 'Data Not Found' );
			}

			if(response == 'empty' || response == 'error') {
				jQuery('.ApiZoneId').val(response.ZoneId).prop('readonly',false);
				 Notiflix.Notify.Info( 'Data Not Found! Please Try Another Employee Id..' );
			}
			
		}
	  });
	});
});

//Add All Zone By Api Calling
function AddAllZoneByApi() {
	var url = "apilistaddzone/";
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
					Notiflix.Notify.Success('Zone Data Insert Successfully');
					window.location.reload();
					return getZoneData();
				} else {
					Notiflix.Loading.Remove(600);
					Notiflix.Notify.Warning( 'Zone Not Found! Please Try Another Zone Id..' );
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