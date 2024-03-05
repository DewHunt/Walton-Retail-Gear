jQuery(function(){
	//Employee Information Modal Status Update Option 
	jQuery('.employee-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var EmpId = jQuery(this).data('id');
		var url = "employeeStatus"+"/"+EmpId;
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
					Notiflix.Loading.Remove(600);
				}
			
				if(response.error){
					Notiflix.Notify.Failure( 'Data Update Failed' );
					Notiflix.Loading.Remove(600);
				}

			}
		});
		
	});

	// Get Employee Data AS MySql View Page   
	function getEmployeeData(){
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		//var url = "employee";
		jQuery.ajax({
		//url:url,
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

	// Add Employee Data
	jQuery('#AddEmployee').submit(function(e){
	  e.preventDefault();
	  jQuery('#search_employee_id').html("");
	  jQuery('#name-error').html("");
	  jQuery('#phone-error').html("");
	  jQuery.ajax({
		url:"addEmployee",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(600);
        },
		success:function(response) {
			console.log(response);
			if(response.errors) {
				if(response.errors.EmployeeName){
					jQuery( '#name-error' ).html( response.errors.name );
				}
				if(response.errors.MobileNumber){
					jQuery( '#phone-error' ).html( response.errors.mobile_number );
				}
				if(response.errors.name){
					jQuery( '#name-error' ).html( response.errors.name );
				}
				if(response.errors.mobile_number){
					jQuery( '#phone-error' ).html( response.errors.mobile_number );
				}
				if(response.errors.email){
					jQuery( '#email-error' ).html( response.errors.email );
				}
			}

			if(response == "success") {
				jQuery("#AddEmployee")[0].reset();
				Notiflix.Notify.Success( 'Data Insert Successfull' );
				Notiflix.Loading.Remove(600);
				setTimeout(function(){// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                    return getEmployeeData();
                }, 1000);
				
			}

			if(response.fail) {
				if(response.errors.name){
					jQuery("#AddEmployee")[0].reset();
					jQuery('#error_field').addClass('has-error');
					jQuery('#error-name').html( response.errors.name[0] );
					Notiflix.Notify.Failure( 'Data Insert Failed' );
					Notiflix.Loading.Remove(600);
				}
			}

		},
		error:function(error){
		  jQuery("#AddEmployee")[0].reset();
		  Notiflix.Notify.Failure( 'Data Insert Failed' );
		  Notiflix.Loading.Remove(600);
		}
	  });
	});

	// Edit Employee Data
	jQuery(document).on("click","#editEmployeeInfo",function(e){
	  e.preventDefault();
	  var EmployeeId = jQuery(this).data('id');
	  var url = "employee"+"/"+EmployeeId+"/edit";
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
		success:function(response){
			console.log(response);
			jQuery('#update_id').val(response.id);
			jQuery('.UpdateApiId').val(response.employee_id);
			jQuery('.UpdateApiName').val(response.name);
			jQuery('.UpdateApiDesignation').val(response.designation);
			jQuery('.UpdateApiMobileNumber').val(response.mobile_number);
			jQuery('.UpdateApiEmail').val(response.email);
			jQuery('.UpdateApiOperatingUnit').val(response.operating_unit);
			jQuery('.UpdateApiProduct').val(response.product);
			jQuery('.UpdateApiDepartment').val(response.department);
			jQuery('.UpdateApiSection').val(response.section);
			jQuery('.UpdateApiSubSection').val(response.sub_section);
			Notiflix.Notify.Success( 'Data Get Successfull' );
			Notiflix.Loading.Remove(600);
			if (response.status == 1){
				jQuery("#option1").prop("checked", true);
			} else {
		  		jQuery("#option2").prop("checked", true);
		  	}
		}
	  });
	});

	// Update Employee Data
	jQuery('#UpdateEmployee').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		jQuery('#update-name-error').html("");
	  	jQuery('#update-phone-error').html("");
		var formData = new FormData(this);
		formData.append('_method', 'put');
	  
		var UpdateId   = jQuery('#update_id').val();
		var data       = jQuery("#UpdateEmployee").serialize();
		
		
		jQuery.ajax({
			url:"employee"+"/"+UpdateId,
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response){
				if(response == "success"){
					//jQuery("#editDelarModal").modal("hide");
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getEmployeeData();
					Notiflix.Loading.Remove(600);
				}
				if(response.errors) {
					if(response.errors.name) {
						jQuery( '#update-name-error' ).html( response.errors.bp_name[0] );
					}
					if(response.errors.mobile_number){
						jQuery( '#update-phone-error' ).html( response.errors.bp_phone[0] );
					}
				}
				if(response == "empExit") {
					Notiflix.Notify.Warning( 'Update Failed Please Try Again.' );
					Notiflix.Loading.Remove(600);
				}
			},
			error:function(error) {
			  jQuery("#UpdateEmployee")[0].reset();
			  Notiflix.Notify.Failure( 'Data Update Failed' );
			}
		});
	});

	//API Search Employee By Id
	jQuery(document).on("click","#search_employee_button",function(e){
	  e.preventDefault();
	  var EmployeeId = jQuery('#search_employee_id').val();
	  var url = "apiemployee"+"/"+EmployeeId;
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
            Notiflix.Loading.Remove(500);
        },
		success:function(response){
			console.log(response);

			if(response.error) {
				jQuery("#AddEmployee")[0].reset();
				Notiflix.Notify.Failure( 'NO Data Found' );
				Notiflix.Loading.Remove(600);
			}

			if(response.success) {
				//jQuery('#employee_id').val(response.EmployeeId).prop('readonly',true);
				jQuery('.ApiId').val(response.success.EmployeeId).prop('readonly',true);
				jQuery('.ApiName').val(response.success.EmployeeName);
				jQuery('.ApiDesignation').val(response.success.Designation);
				jQuery('.ApiMobileNumber').val(response.success.MobileNumber);
				jQuery('.ApiEmail').val(response.success.Email);
				jQuery('.ApiOperatingUnit').val(response.success.OperatingUnit);
				jQuery('.ApiProduct').val(response.success.Product);
				jQuery('.ApiDepartment').val(response.success.Department);
				jQuery('.ApiSection').val(response.success.Section);
				jQuery('.ApiSubSection').val(response.success.SubSection);
				jQuery('.ApiStatus').val(response.success.status);
				jQuery('.EmpPassword').val(response.success.password).show();

				/*if(response == 'success') {
					jQuery("#AddEmployee")[0].reset();
					jQuery('#AddEmployeeModal').modal('hide');
					Notiflix.Notify.Success('Employee Data Insert Successfully');
				}*/  
			} 

			if(response == 'empty' || response == 'error') {
				jQuery('.ApiId').val(response.EmployeeId).prop('readonly',false);
				Notiflix.Notify.Info( 'Data Not Found! Please Try Another Employee Id..' );
			}
			
		}
	  });
	});
});