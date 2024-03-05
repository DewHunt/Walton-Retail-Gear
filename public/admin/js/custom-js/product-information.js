jQuery(function(){
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

	// Get Product Data AS MySql View Page   
	function getProductData() {
		var query       = $('#serach').val();
		var column_name = $('#hidden_column_name').val();
		var sort_type   = $('#hidden_sort_type').val();
		var page        = $('#hidden_page').val();
		//var url = "product";
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
	jQuery('#AddProduct').submit(function(e) {
	  e.preventDefault();
	  jQuery('#model-number-error').html("");
	  //jQuery('#code-error').html("");
	  jQuery('#mrp-error').html("");
	  jQuery('#msdp-error').html("");
	  jQuery('#msrp-error').html("");
	  jQuery.ajax({
		url:"product.add",
		method:"POST",
		data:new FormData(this),
		dataType:'JSON',
		contentType: false,
		cache: false,
		processData: false,

		success:function(response)	{
			console.log(response);
			if(response.errors) {
				if(response.errors.product_model){
					jQuery( '#model-number-error' ).html( response.errors.product_model[0] );
				}
				/*if(response.errors.product_code){
					jQuery( '#code-error' ).html( response.errors.product_code[0] );
				}*/
				if(response.errors.mrp_price){
					jQuery( '#mrp-error' ).html( response.errors.mrp_price[0] );
				}
				if(response.errors.msdp_price){
					jQuery( '#msdp-error' ).html( response.errors.msdp_price[0] );
				}
				if(response.errors.msrp_price){
					jQuery( '#msrp-error' ).html( response.errors.msrp_price[0] );
				}
				if(response.errors.product_id){
					Notiflix.Notify.Failure('Invalid Product.Please Contact Your Higher Authorized');
				}
			}

			if(response == "error") {
				Notiflix.Notify.Failure('Prduct Add Failed');
			}

			if(response == "success") {
				jQuery("#AddProduct")[0].reset();
				jQuery(".btnCloseModal").click();
				Notiflix.Notify.Success('Prduct Add Successfull');
				return getProductData();
			}
		},
		error:function(error)	{
			Notiflix.Notify.Failure('Prduct Add Failed');
		}
	  });
	});

	// Edit Product Data
	jQuery(document).on("click","#editProductInfo",function(e){
		e.preventDefault();
		var ProductId = jQuery(this).data('id');
		var url = "product.edit"+"/"+ProductId;
		jQuery.ajax({
			url:url,
			type:"GET",
			dataType:"JSON",
			success:function(response)	{
				//console.log(response);
				jQuery('#product_master_id').val(response.product_master_id);
				jQuery('.productId').val(response.product_id).prop('readonly',true);
				jQuery('.productCode').val(response.product_code);
				jQuery('.productType').val(response.product_type);
				jQuery('.productModel').val(response.product_model);
				jQuery('.productCategory').val(response.category2);
				jQuery('.productPrice').val(response.mrp_price);
				jQuery('.productMsdp').val(response.msdp_price);
				jQuery('.productMsrp').val(response.msrp_price);
			}
		});
	});

	// Update Product Data
	jQuery('#UpdateProduct').on("submit", function(arg){
		jQuery.ajaxSetup({
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			}
		});
		arg.preventDefault();
		jQuery('#update-model-number-error').html("");
		jQuery('#update-code-error').html("");
		jQuery('#update-mrp-error').html("");
		jQuery('#update-msdp-error').html("");
		jQuery('#update-msrp-error').html("");


		var formData = new FormData(this);
		formData.append('_method', 'post');
	  
		var productId   = jQuery('#product_master_id').val();
		var data        = jQuery("#UpdateProduct").serialize();
		
		jQuery.ajax({
			//url:"product"+"/"+productId,
			url:"product.update",
			type:"POST",
			data:formData,
			dataType:'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success:function(response)	{

				if(response.errors) {
					if(response.errors.product_model) {
						jQuery( '#update-model-number-error' ).html( response.errors.product_model[0] );
					}
					if(response.errors.product_code){
						jQuery( '#update-code-error' ).html( response.errors.product_code[0] );
					}
					if(response.errors.mrp_price){
						jQuery( '#update-mrp-error' ).html( response.errors.mrp_price[0] );
					}
					if(response.errors.msdp_price){
						jQuery( '#update-msdp-error' ).html( response.errors.msdp_price[0] );
					}
					if(response.errors.msrp_price){
						jQuery( '#update-msrp-error' ).html( response.errors.msrp_price[0] );
					}
				}

				if(response == "success")	{
					jQuery(".btnCloseModal").click();
					Notiflix.Notify.Success( 'Data Update Successfull' );
					return getProductData();
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

	//API Search Product By Id
	jQuery(document).on("click","#search_product_button",function(e){
	  e.preventDefault();
	  var ProductId = jQuery('#search_product_id').val();
	  var url = "apiproduct"+"/"+ProductId;
	  jQuery.ajax({
		url:url,
		type:"GET",
		dataType:"JSON",
		beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
		success:function(response) {
			console.log(response);
			if(response) {
				Notiflix.Loading.Remove();
				//jQuery('#Apiproduct_master_id').val(response.product_master_id);
				jQuery('.ApiproductId').val(response.ProductID).prop('readonly',true);
				jQuery('.ApiproductCode').val(response.product_code);
				jQuery('.ApiproductType').val(response.product_type);
				jQuery('.ApiproductModel').val(response.Model);
				jQuery('.ApiproductCategory').val(response.category2);
				jQuery('.ApiproductPrice').val(response.Price);
				jQuery('.ApiproductMsdp').val(response.MSDP);
				jQuery('.ApiproductMsrp').val(response.MSRP);

				if(response == 'success') {
					jQuery("#AddProduct")[0].reset();
					jQuery('#AddProductModal').modal('hide');
					Notiflix.Notify.Success('Product Data Insert Successfully');
				}  
			} else {
				Notiflix.Notify.Failure( 'Data Not Found' );
				jQuery("#AddProduct")[0].reset();
				Notiflix.Loading.Remove(600);
			}

			if(response == 'empty' || response == 'error') {
				jQuery('.ApiproductId').val(response.ProductID).prop('readonly',false);
				Notiflix.Notify.Info( 'Data Not Found! Please Try Another Product Id..' );
			}
			
		}
	  });
	});

});
//Add All Product By Api Calling
function AddAllProductByApi() {
	var url = "apilistaddproduct/";
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
					Notiflix.Notify.Success('Product Data Insert Successfully');
				} else {
					Notiflix.Loading.Remove(600);
					Notiflix.Notify.Warning( 'Data Not Found! Please Try Another Product Id..' );
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
