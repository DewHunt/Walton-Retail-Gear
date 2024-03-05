jQuery(function(){
	
	jQuery('.push-notification-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var getId = jQuery(this).data('id');
		var url = "pushNotificationStatus"+"/"+getId;
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){
				if(response.success){
					Notiflix.Notify.Success( 'Notification Update Successfull' );
				}
			
				if(response.error){
					Notiflix.Notify.Failure( 'Notification Update Failed' );
				}
			}
		});
	});

	function getPushNotificationData() {
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

	// Add  Data
	jQuery('#AddPushNotification').submit(function(e) {
	  e.preventDefault();
	  jQuery('#title-error').html("");
	  jQuery('#message-error').html("");
	  jQuery.ajax({
		url:"pushNotification",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,

		success:function(response)	{

			if(response.errors) {
				if(response.errors.title){
					jQuery( '#title-error' ).html( response.errors.title[0] );
				}
				if(response.errors.message){
					jQuery( '#message-error' ).html( response.errors.message[0] );
				}
			}

			if(response == "error") {
				Notiflix.Notify.Failure( 'Notification Save Failed' );
			}

			if(response == "success") {
				jQuery("#AddPushNotification")[0].reset();
				jQuery(".btnCloseModal").click();
				Notiflix.Notify.Success( 'Notification Save Successfull' );
				return getPushNotificationData();
			}
		},
		error:function(error)	{
			jQuery("#AddPushNotification")[0].reset();
			Notiflix.Notify.Failure( 'Notification Save Failed' );
		}
	  });
	});

	// Edit Data
	jQuery(document).on("click","#editPushNotificationInfo",function(e){
		e.preventDefault();
		var getId 	= jQuery(this).data('id');
		var url 	= "pushNotification"+"/"+getId+"/edit";
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:"JSON",
			success:function(response)	{
				console.log(response);
				jQuery('#updateId').val(response.id);
				jQuery('.getTitle').val(response.title);
				jQuery('.getMessage').val(response.message);
				jQuery('#getZone').val(JSON.parse(response.zone)).change();
				jQuery('#getCategory').val(JSON.parse(response.category)).change();
				jQuery('#getMessageGroup').val(JSON.parse(response.message_group)).change();
				if (response.status == 1){
					jQuery("#option1").prop("checked", true);
				} else {
			  		jQuery("#option2").prop("checked", true);
			  	}
			}
		});
	});

	// Update  Data
	jQuery('#UpdatePushNotification').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		jQuery('#update-title-error').html("");
		jQuery('#update-message-error').html("");

		var formData = new FormData(this);
		formData.append('_method', 'put');
	  
		var getId   = jQuery('#updateId').val();
		var data    = jQuery("#UpdatePushNotification").serialize();
		
		jQuery.ajax({
			url:"pushNotification"+"/"+getId,
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response)	{

				if(response.errors) {
					if(response.errors.title){
						jQuery( '#update-title-error' ).html( response.errors.title[0] );
					}
					if(response.errors.message){
						jQuery( '#update-message-error' ).html( response.errors.message[0] );
					}
				}

				if(response == "success")	{
					jQuery(".btnCloseModal").click();
					Notiflix.Notify.Success( 'Notification Update Successfull' );
					return getPushNotificationData();
					console.log(response);
					Notiflix.Loading.Remove(600);
				}
			
				if(response == "error")	{
					Notiflix.Notify.Failure( 'Notification Update Failed' );
					console.log(response);
				}
			}
		});
	});

	// Get Notification Data
	jQuery(document).on("click","#getPushNotificationInfo",function(e){
		e.preventDefault();
		$('.success').html("");
	    $('.failure').html("");
		var getId 	= jQuery(this).data('id');
		var url 	= "pushNotification"+"/"+getId+"show";
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:"JSON",
			success:function(response)	{
				console.log(response);
				jQuery('#sendId').val(response.id);
				jQuery('.sendTitle').val(response.title);
				jQuery('.sendMessage').val(response.message);
				jQuery('#sendZone').val(JSON.parse(response.zone)).change();
				jQuery('#sendCategory').val(JSON.parse(response.category)).change();
				jQuery('#sendMessageGroup').val(JSON.parse(response.message_group)).change();
			}
		});
	});

	// Send Notification Data
	jQuery('#SendPushNotification_old').submit(function(e) {
	  	e.preventDefault();
	  	var getId 	= $('#sendId').val();
		//var url 	= "SendPushNotification"+"/"+getId;
		var url 	= "SendPushNotification";
		jQuery.ajax({
			url:url,
			type:"POST",
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){
				if(response == "success") {
					jQuery(".btnCloseModal").click();
					Notiflix.Notify.Success( 'Ntification Send Successfull' );
				} else {
				    
					Notiflix.Notify.Failure( 'Notification Send Failed' );
				}
				
			}
		});
	});

	jQuery('#SendPushNotification').submit(function(e) {
	  e.preventDefault();
	  $('.success').html("");
	  $('.failure').html("");
	  jQuery.ajax({
		url:"sendWebNotification",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,
		success:function(response)	{
			//console.log(response);
			//console.log(response.failure);
			jQuery('.success').html(response.success);
			jQuery('.failure').html(response.failure);

			if(response == "error") {
				Notiflix.Notify.Failure( 'Notification Send Failed' );
			}
			if(response.success) {
				jQuery(".btnCloseModal").click();
				Notiflix.Notify.Success( 'Notification Send Successfull hell' );
			}
		},
		error:function(error)	{
			Notiflix.Notify.Failure( 'Notification Send Failed' );
		}
	  });
	});
});

