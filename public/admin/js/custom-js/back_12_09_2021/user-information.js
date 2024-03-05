jQuery(function(){
	// Get  Data AS MySql View Page   
	function getUserData(){
	  var url = "getUser";
	  jQuery.ajax({
		url:url,
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
	jQuery('#AddUser').submit(function(e){
	  e.preventDefault();
	  jQuery('#user-email-error').html("");
	  jQuery('#user-password-error').html("");
	  jQuery('#user-confirm-password-error').html("");
	  jQuery.ajax({
		url:"AddToUser",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,

		success:function(response){
		console.log(response);
		if(response.errors) {
			if(response.errors.email){
				jQuery( '#user-email-error' ).html( response.errors.email[0] );
			}
			if(response.errors.password){
				jQuery( '#user-password-error' ).html( response.errors.password[0] );
			}
			if(response.errors.confirm-password){
				jQuery( '#user-confirm-password-error' ).html( response.errors.confirm-password[0] );
			}
		}


		if(response == "error"){
			Notiflix.Notify.Failure( 'Data Insert Failed' );
		}

		if(response == "success"){
			jQuery("#AddUser")[0].reset();
			$(".btnCloseModal").click();
			Notiflix.Notify.Success( 'Data Insert Successfull' );
			return getUserData();
		}

		  if(response.fail) {
			if(response.errors.name){
			  jQuery("#AddUser")[0].reset();
			  $(".btnCloseModal").click();
			  jQuery('#error_field').addClass('has-error');
			  jQuery('#error-name').html( response.errors.name[0] );
			  Notiflix.Notify.Failure( 'Data Insert Failed' );
			}
		  }

		},
		error:function(error){
		  jQuery("#AddUser")[0].reset();
		  Notiflix.Notify.Failure( 'Data Insert Failed' );
		}
	  });
	});

	// Edit  Data
	jQuery(document).on("click","#editUserInfo",function(e){
	  e.preventDefault();
	  var UserId = jQuery(this).data('id');
	  var url = "ShowUser"+"/"+UserId;
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
			jQuery('.userName').val(response.name);
			jQuery('.userEmail').val(response.email);
			jQuery('#old_password').val(response.password);

			if(response.employee_id > 0){
				jQuery(".vEmpId"+response.employee_id).prop("selected", true);
				jQuery(".empId").prop("disabled", true);
				jQuery('.userName').prop("readonly", true);
				jQuery('#update_employee_id').val(response.employee_id);

			}
			else {
				jQuery(".empId").prop("disabled", true);
				jQuery('.userName').prop("readonly", true);
				jQuery('#update_employee_id').val(0);
			}

			if (response.status == 1){
				jQuery("#option1").prop("checked", true);
			} else {
		  		jQuery("#option2").prop("checked", true);
		  	}
		}
	  });
	});

	// Update Data
	jQuery('#UpdateUser').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		jQuery('#uuser-email-error').html("");
		jQuery('#uuser-password-error').html("");
		jQuery('#uuser-confirm-password-error').html("");

		var formData = new FormData(this);
		formData.append('_method', 'put');
	  
		var userId   = jQuery('#update_id').val();
		var data     = jQuery("#UpdateUser").serialize();
		
		jQuery.ajax({
			url:"UpdateUser"+"/"+userId,
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response) {

				if(response.errors) {
					if(response.errors.email){
						jQuery( '#uuser-email-error' ).html( response.errors.email[0] );
					}
					if(response.errors.password){
						jQuery( '#uuser-password-error' ).html( response.errors.password[0] );
					}
					if(response.errors.confirm-password){
						jQuery( '#uuser-confirm-password-error' ).html( response.errors.confirm-password[0] );
					}
				}


				if(response == "user-success") {
					Notiflix.Notify.Success('User Update Successfull');
					return getUserData();
				}
			
				if(response == "error"){
					Notiflix.Notify.Failure('Data Update Failed');
				}
			
				if(response.fail) {
					if(response.errors.name){
						jQuery('#error_field').addClass('has-error');
						jQuery('#error-name').html( response.errors.name[0] );
						Notiflix.Notify.Failure( 'Data Update Failed' );
					}
				}
			}
		});
	});

});
