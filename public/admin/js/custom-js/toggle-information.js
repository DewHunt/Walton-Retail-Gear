jQuery(function(){
	//Zone Information Modal Status Update Option 
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
	//Retailer Status Update Option 
	jQuery('.retailer-toggle-class').change(function(e) {
		e.preventDefault();
		alert('Hi');
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
	//Push Status Update Option 
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
	//Product Information Modal Status Update Option 
	jQuery('.product-toggle-class').change(function(e) {
		e.preventDefault();
		var status = jQuery(this).prop('checked') == true ? 1 : 0; 
		var ProductId = jQuery(this).data('id');
		var url = "productStatus"+"/"+ProductId;
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
});