<script
        src="{{asset('public/admin/js/jquery-3.6.0.js')}}"
              integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
              crossorigin="anonymous"></script>
<script type="text/javascript" src="{{asset('public/admin/js/jquery.min.js')}}"></script>

<!--PDF & Excel Formation data export start -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

{{-- <script type="text/javascript" src="{{asset('public/admin/js/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/html2canvas.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/xlsx.full.min.js')}}"></script> --}}
<!--PDF & Excel Formation data export End -->


<!-- Latest compiled JavaScript -->
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/vendor.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bundle.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/beacon.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/notiflix-1.9.1.js')}}"></script>
<script type="text/javascript" charset="utf8" src="{{asset('public/admin/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap-toggle.min.js')}}"></script>
<!-- bootstrap datepicker -->
<script type="text/javascript" src="{{ asset('public/admin/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/admin/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Select2 -->
<script type="text/javascript" src="{{ asset('public/admin/select2/js/select2.full.min.js') }}"></script>


<!-- DataTables  & Plugins -->
<script src="{{ asset('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script type="text/javascript">
  var APP_URL = {!! json_encode(url('/')) !!};
</script>

<script>
$(document).ready(function(){
    $('.agentDiv').hide();
    $('.bankDiv').hide();
});
jQuery(function () {
    jQuery("#example1").DataTable({
        "responsive": true,
        "paging": true,
        "pageLength" :2,
        "lengthChange": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "buttons": ["excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    jQuery('#example2').DataTable({
        "pageLength": 100,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
    jQuery("#example3").DataTable({
        "responsive": true,
        "searching": true,
        "paging": false,
        "pageLength" :5000,
        "lengthChange": true,
        "ordering": true,
        "autoWidth": false,
        "buttons": ["excel", "pdf", "print"]
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
});
</script>

<!--PDF & Excel Formation data export Start -->
<script>
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();
var today = mm + '/' + dd + '/' + yyyy;

function ExportToExcel(type, fn, dl) {
    var elt = document.getElementById('dataExport');
    var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
        XLSX.writeFile(wb, fn || ('ExportExcel_'+today+'.'+ (type || 'xlsx')));
}

jQuery("body").on("click", "#btnPdf", function (e) {
    e.preventDefault();
    html2canvas($('#dataExport')[0], {
        onrendered: function (canvas) {
            var data = canvas.toDataURL();
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download("ExportPdf_"+today+".pdf");
        }
    });
});
</script>
<!--PDF & Excel Formation data export End -->
<script>
jQuery.noConflict();
jQuery(document).ready(function() {
    jQuery('.CustomdataTable').dataTable({
        "aLengthMenu": [[100, 200, 500, 1000, -1], [100, 200, 500, 1000, "All"]],
        "iDisplayLength": 100,
        "bPaginate": false,
        "bInfo":false
    });
    //Date picker
    jQuery('.datepicker').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
    jQuery('#example').DataTable();
    //Initialize Select2 Elements
    jQuery('.select2').select2();

    //Initialize Select2 Elements
    jQuery('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    jQuery('.time').datetimepicker({
        format: 'hh:mm:ss'
    });
});
function checkPaymentType($payment_type) {
    if($payment_type == 1) 
    {
        var PaymentType         = $('#UpdateApiRetailerPaymentType').val();
        var PaymentNumberVal    = $('#UpdateApiRetailerPaymentNumber').val();

        $('.paymentNumber').html('<input type="text" name="agent_name" class="form-control mfc_name" placeholder="Enter Agent Name Ex:Bkash,Nogod,Rocket" required=""/><br/><input type="text" name="payment_number" class="form-control mfc_field" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Enter Agent Number Ex: 98586254781" maxlength="11"  minlength="11" required=""/>');

        if(PaymentType == 1) {
            $('.mfc_field').val(PaymentNumberVal);
            $('.bank_field').empty();
            $('.bank_name').empty();
        }

        $('.bank_field').remove();
        $('.bank_name').remove();
    } 
    else 
    {
        var PaymentType         = $('#UpdateApiRetailerPaymentType').val();
        var PaymentNumberVal    = $('#UpdateApiRetailerPaymentNumber').val();

         $('.paymentNumber').html('<input type="text" name="bank_name" class="form-control bank_name" placeholder="Enter Bank Name Ex:DBBL,Jamuna Bank" required=""/><br/><input type="text" name="payment_number" class="form-control bank_field" placeholder="Bank Account Number Ex:227 103 xxxxx"  minlength="11" required=""/>');

        if(PaymentType == 2) {
            $('.bank_field').val(PaymentNumberVal);
            $('.mfc_field').empty();
            $('.mfc_name').empty();
        }
        $('.mfc_field').remove();
        $('.mfc_name').remove();
    }
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    //if (charCode > 31 && (charCode < 48 || charCode > 57))
    if (!(charCode.shiftKey == false && (charCode == 46 || charCode == 8 || charCode == 37 || charCode == 39 || (charCode >= 48 && charCode <= 57)))) {
        evt.preventDefault();
        alert('Only Number Allowed')
    }

    return true;
}
</script>

<!--Pagination New Script Start-->
<script>
function clear_icon() {
    $('#id_icon').html('');
    $('#post_title_icon').html('');
}

function fetch_data(page, sort_type, sort_by, query) {
    $.ajax({
        url:"?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
        type:"get",
        success:function(data) {
            $('tbody').html('');
            $('tbody').html(data);
            $(this).data('toggle-on', true);
            jQuery('.toggle').each(function() {
                $(this).toggles({
                    on: $(this).data('toggle-on')
                });
            });
            jQuery('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
            
            /*
            $.getScript( "https://localhost/retail-gear/public/admin/js/custom-js/brand-promoter-information.js" )
              .done(function( script, textStatus ) {
                alert('Successfully loaded script');
              })
              .fail(function( jqxhr, settings, exception ) {
                alert('Failed to load script');
            });
            
            $.getScript("https://localhost/retail-gear/public/admin/js/custom-js/brand-promoter-information.js");
            alert(bpJs);
            */
            /*
            var bpJs        = APP_URL+"/public/admin/js/custom-js/brand-promoter-information.js";
            var pJs         = APP_URL+"/public/admin/js/custom-js/product-information.js";
            var empJs       = APP_URL+"/public/admin/js/custom-js/employee-information.js";
            var zoneJs      = APP_URL+"/public/admin/js/custom-js/zone-information.js";
            var retailJs    = APP_URL+"/public/admin/js/custom-js/retailer-information.js";
            var dealerJs    = APP_URL+"/public/admin/js/custom-js/dealer-information.js";
            var userJs      = APP_URL+"/public/admin/js/custom-js/user-information.js";
            var prebookingJs    = APP_URL+"/public/admin/js/custom-js/prebooking.js";
            var pushnotificationJs  = APP_URL+"/public/admin/js/custom-js/push-notification.js";
            
            $.getScript(bpJs);
            $.getScript(pJs);
            $.getScript(empJs);
            $.getScript(zoneJs);
            $.getScript(retailJs);
            $.getScript(dealerJs);
            $.getScript(userJs);
            $.getScript(prebookingJs);
            $.getScript(pushnotificationJs);
            */
            
            
            var toggleJs  = APP_URL+"/public/admin/js/custom-js/toggle-information.js";
            jQuery.getScript(toggleJs);
        }
    })
}

$(document).ready(function() {
    $(document).on('keyup', '#serach', function() {
        var query       = $('#serach').val();
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var page        = $('#hidden_page').val();
        fetch_data(page, sort_type, column_name, query);
    });

    $(document).on('click', '.sorting', function(){
        var column_name     = $(this).data('column_name');
        var order_type      = $(this).data('sorting_type');
        var reverse_order   = '';
        if(order_type == 'asc') {
            $(this).data('sorting_type', 'desc');
            reverse_order = 'desc';
            clear_icon();
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
        }
        if(order_type == 'desc') {
            $(this).data('sorting_type', 'asc');
            reverse_order = 'asc';
            clear_icon
            $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        var page    = $('#hidden_page').val();
        var query   = $('#serach').val();
        fetch_data(page, reverse_order, column_name, query);
    });

    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var column_name = $('#hidden_column_name').val();
        var sort_type   = $('#hidden_sort_type').val();
        var query       = $('#serach').val();
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, query);
    });
    // jQuery('.btnCloseModal').trigger('click');
    // jQuery('.btnCloseModal').mousedown();
    // jQuery('.close').click();
});
</script>
<!--Pagination New Script Start-->

<script>
jQuery(function(){
  jQuery.ajaxSetup({
    headers: { 'X-CSRF-Token' : '{{csrf_token()}}' }
  });
});
</script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/dealer-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/product-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/prebooking.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/employee-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/zone-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/retailer-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/brand-promoter-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/user-information.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/custom-js/push-notification.js')}}"></script>
<script>
jQuery(document).ready(function(){
    @if($message = Session::get('success'))
        Notiflix.Notify.Success( '{{ $message }}' );
    $('.active_list').addClass("active");
    $('.active_form').removeClass('active');
    @elseif($message = Session::get('error'))
        Notiflix.Notify.Failure( '{{ $message }}' );
    $('.active_form').addClass("active");
    $('.active_list').removeClass('active');
    @elseif($message = Session::get('warning'))
        Notiflix.Notify.Warning('{{ $message }}' );
    @elseif($message = Session::get('info'))
        Notiflix.Notify.Info( '{{ $message }}' );
    //@else
        //Notiflix.Notify.Failure( '{{ $message }}' );              
    @endif
});
</script>
<script type="text/javascript">
jQuery('.Number').keypress(function (event) {
    var keycode = event.which;
    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
        alert('Only Number Allowed')
    }
});
jQuery('.incentive-update-toggle').change(function(e) {
    e.preventDefault();
    var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
    var IncentiveId = jQuery(this).data('id');
    var url         = "incentive.status/"+IncentiveId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            //console.log(response.success)
            if(response.success) {
                Notiflix.Notify.Success('Incentive Update Successfull');
            }
            if(response.error) {
                Notiflix.Notify.Failure('Incentive Update Failed');
            }
        }
    });
});
jQuery('.award-toggle-class').change(function(e) {
    e.preventDefault();
    var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
    var AwardId     = jQuery(this).data('id');
    var url         = "award.status"+"/"+AwardId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response.success) {
                Notiflix.Notify.Success('Award Update Successfull');
            }
            if(response.error) {
                Notiflix.Notify.Failure('Award Update Failed');
            }

        }
    });
});
// ime Product Data View
jQuery(document).on("click","#viewProductInfo",function(e){
  e.preventDefault();
  var ProductId = jQuery(this).data('id');
  var url = "imeProductDetails"+"/"+ProductId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);

        if(response == "error") {
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('.product_model').html(response.product_model);
            jQuery('.product_code').html(response.product_code);
            jQuery('.product_type').html(response.product_type);
            jQuery('.mrp_price').html(response.mrp_price);
            jQuery('.msdp_price').html(response.msdp_price);
            jQuery('.msrp_price').html(response.msrp_price);
        }
    }
  });
});
// Order Details Data View
jQuery(document).on("click","#viewOrderDetails",function(e){
    e.preventDefault();
    $('#salesInfo').html("");
    $('#itemList').html("");
    var orderId = jQuery(this).data('id');
    var url = "OrderDetailsView"+"/"+orderId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(300);

            if(response == "error") {
                Notiflix.Notify.Failure( 'Data Not Found' );
                setTimeout(function() {// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 1000);
            }

            if(response) {
                jQuery('#salesInfo').append(response.salesInfo);
                jQuery('#itemList').append(response.itemList);
                jQuery('#saleId').val(response.saleId);
            }
        }
    });
});
// Sales Return
jQuery(document).on("click","#salesReturn",function(e) {
    e.preventDefault();
    var orderId = $('#saleId').val();
    var url     = "salesReturn"+"/"+orderId;

    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(300);

            if(response == "success") {
                Notiflix.Notify.Success('Sales Return Successfully');
                setTimeout(function() {// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 1000);
            }

            if(response == "error") {
                Notiflix.Notify.Failure('Sales Return Failed');
                setTimeout(function() {// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 1000);
            }
        },
        error:function(error){
          Notiflix.Notify.Failure('Sales Return Failed');
        }
    });
});
// Add Offer Data
jQuery('#AddOffer').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"promoOffer.add",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response.errors) {
            if(response.errors.title){
                jQuery( '#title-error' ).html( response.errors.title );
            }
            if(response.errors.sdate){
                jQuery( '#sdate-error' ).html( response.errors.sdate );
            }
            if(response.errors.edate){
                jQuery( '#edate-error' ).html( response.errors.edate );
            }
            if(response.errors.offer_pic){
                jQuery( '.offer-pic-error' ).html( response.errors.offer_pic );
            }
        }
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Insert Failed' );
            Notiflix.Loading.Remove(600);
        }

        if(response == "success"){
            jQuery("#AddOffer")[0].reset();
            Notiflix.Notify.Success( 'Data Insert Successfull' );
            Notiflix.Loading.Remove(600);
            return getOfferData();
        }

        if(response.fail) {
            if(response.errors.name){
                jQuery("#AddOffer")[0].reset();
                jQuery('#error_field').addClass('has-error');
                jQuery('#error-name').html( response.errors.name[0] );
                Notiflix.Notify.Failure( 'Data Insert Failed' );
                Notiflix.Loading.Remove(600);
            }
        }
    },
    error:function(error){
      jQuery("#AddOffer")[0].reset();
      Notiflix.Notify.Failure( 'Data Insert Failed' );
      Notiflix.Loading.Remove(600);
    }
  });
});
//Get Offer
function getOfferData() {
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
//Edit Offer
jQuery(document).on("click","#editOfferInfo",function(e){
  e.preventDefault();
  var offerId = jQuery(this).data('id');
  var url = "promoOffer.edit"+"/"+offerId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response.offerInfo.offer_for);
        jQuery('#update_id').val(response.offerInfo.id);
        jQuery('.utitle').val(response.offerInfo.title);
        jQuery('.usdate').val(response.offerInfo.sdate);
        jQuery('.uedate').val(response.offerInfo.edate);
        jQuery('.uzone').val(response.offerInfo.zone);

        if(response.offerInfo.offer_for == 'all') {
            jQuery(".uall").prop("selected", true);
        } else if (response.offerInfo.offer_for == 'bp') {
            jQuery(".ubp").prop("selected", true);
        } else {
            jQuery(".uretailer").prop("selected", true);
        }
        Notiflix.Notify.Success( 'Data Get Successfull' );
        Notiflix.Loading.Remove(600);
        if (response.offerInfo.status == 1){
            jQuery("#option1").prop("checked", true);
        } else {
            jQuery("#option2").prop("checked", true);
        }

        var imgUrl = '<img src="'+response.offerInfo.offer_pic+'" width="100%" />';
        $('#img-tag').html(imgUrl);

        var returnedZoneData = JSON.parse(response.offerInfo.zone);
        $.each(returnedZoneData, function(index, value) {
          console.log(value);
           jQuery("#update_zone").append('<option selected="selected" value="'+value+'">'+value+'</option>');
        });
    }
  });
});
//Update Offer
jQuery('#UpdateOffer').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    var formData = new FormData(this);
    formData.append('_method', 'post');
  
    var UpdateId   = jQuery('#update_id').val();
    var data       = jQuery("#UpdateOffer").serialize();
    jQuery.ajax({
        url:"promoOffer.update",
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response == "success") {
                Notiflix.Notify.Success( 'Data Update Successfull' );
                return getOfferData();
                Notiflix.Loading.Remove(600);
            }
        },
        error:function(error) {
          jQuery("#updateOffer")[0].reset();
          Notiflix.Notify.Failure( 'Data Update Failed' );
        }
    });
});
//Offer Modal Status
jQuery('.offer-toggle-class').change(function(e) {
e.preventDefault();
var status = jQuery(this).prop('checked') == true ? 1 : 0; 
var updateId = jQuery(this).data('id');
var url = "promoOffer.status"+"/"+updateId;
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
// Message Reply Data View
jQuery('#ReplyMessage').submit(function(e){
  e.preventDefault();
  $('#sendMessage').html('');
  $('#replyMessage').html('');
  jQuery.ajax({
    url:"message.reply",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,

    success:function(response) {
        Notiflix.Loading.Arrows('Reply Message Processing');
        console.log(response);
        Notiflix.Loading.Remove(300);
        if(response.error) {
            Notiflix.Notify.Failure('Message Not Reply');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response.sendMessage) {
            if(response.status == 'success')
            {
                Notiflix.Notify.Success('Message Reply Successfully');
                jQuery('#messageId').val(response.messageId);
                jQuery('#replyId').val(response.replyId);
                //jQuery('#sendMessage').append(response.sendMessage);
                jQuery('#replyMessage').append(response.replyMessage);
                $('#reply_message').val("");
            }
            
        }
    },
    error:function(error){
      Notiflix.Notify.Failure('Message Reply Failed.Please Try Again');
    }
  });
});
// Message Details Data View
jQuery(document).on("click","#MessageDetailsView",function(e){
  e.preventDefault();
  $('#reply_message').html("");
  $('#sendMessage').html('');
  $('#replyMessage').html('');
  var messageId = $('#MsgId').val();
  var replyId   = jQuery(this).data('id');
  var url = "message.details"+"/"+replyId+"/"+messageId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
        Notiflix.Loading.Remove(900);
    },
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);
        if(response == "error") {
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response.sendMessage) {
            jQuery('#messageId').val(response.messageId);
            jQuery('#replyId').val(response.replyId);
            //jQuery('#sendMessage').append(response.sendMessage);
            jQuery('#replyMessage').append(response.replyMessage);
            $('#reply_message').html("");
        }
    }
  });
});
//Edit Leave
jQuery(document).on("click","#leaveEdit",function(e){
  e.preventDefault();
  jQuery("#currentMonthBPLeave").html('');
  var leaveId = jQuery(this).data('id');
  var url = "editLeave"+"/"+leaveId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response){
        console.log(response.leaveInfo.zone);
        jQuery('#update_id').val(response.leaveInfo.id);
        jQuery('.leave_type').val(response.leaveInfo.leave_type);
        jQuery('.start_date').val(response.leaveInfo.start_date);
        jQuery('.total_day').val(response.leaveInfo.total_day);
        jQuery('.start_time').val(response.leaveInfo.start_time);
        jQuery('.reason').val(response.leaveInfo.reason);

        Notiflix.Notify.Success( 'Data Get Successfull' );
        Notiflix.Loading.Remove(600);
        if (response.leaveInfo.status == 'Approved'){
            jQuery("#option1").prop("selected", true);
        }else if (response.leaveInfo.status == 'Pending'){
            jQuery("#option2").prop("selected", true);
        } else {
            jQuery("#option3").prop("selected", true);
        }

        jQuery("#leaveType").append(response.leaveType);
        jQuery("#leaveReason").append(response.leaveReason);
        jQuery("#currentMonthBPLeave").append(response.leaveList);
    }
  });
});
//Update Leave
jQuery('#leaveUpdate').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    var formData = new FormData(this);
    formData.append('_method', 'put');
  
    var UpdateId   = jQuery('#update_id').val();
    var data       = jQuery("#leaveUpdate").serialize();
    jQuery.ajax({
        url:"updateLeave"+"/"+UpdateId,
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response == "success") {
                Notiflix.Notify.Success( 'Data Update Successfull' );
                //return getOfferData();
                Notiflix.Loading.Remove(600);
                window.location.reload();
            }
        },
        error:function(error) {
          jQuery("#leaveUpdate")[0].reset();
          Notiflix.Notify.Failure( 'Data Update Failed' );
        }
    });
});
//Bp Attendance Modal
jQuery(document).on("click","#bpAttendanceDetails",function(e){
  e.preventDefault();
  jQuery('#attendanceList').html("");
  var attendanceId = jQuery(this).data('id');
  var url = "attendanceDetailsView"+"/"+attendanceId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response){
        console.log(response);
        //Notiflix.Loading.Remove(300);

        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            Notiflix.Loading.Remove(800);
            jQuery('#attendanceList').append(response.attendanceList);
        }
    }
  });
});
//Incentive Report Modal
jQuery(document).on("click","#bpSaleIncentiveDetails",function(e){
  e.preventDefault();
  $('#incentiveReportList').html('');
  var incentiveId = jQuery(this).data('id');
  var url = "incentiveDetailsView"+"/"+incentiveId+"/"+0;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);

        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#incentiveReportList').append(response.incentiveReportList);
        }
    }
  });
});
jQuery(document).on("click","#retailSaleIncentiveDetails",function(e){
  e.preventDefault();
  $('#incentiveReportList').html('');
  var incentiveId = jQuery(this).data('id');
  var url = "incentiveDetailsView"+"/"+0+"/"+incentiveId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response){
        console.log(response);
        Notiflix.Loading.Remove(300);

        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#incentiveReportList').append(response.incentiveReportList);
        }
    }
  });
});
//API Search Product By Id
jQuery(document).on("click","#search_imei_button",function(e){
  e.preventDefault();
  $('#imeResultInfo').html('');
  var imeNumber = jQuery('#search_ime').val();
  var url = "checkImei"+"/"+imeNumber;
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
        if(response) {
            console.log(response);
            Notiflix.Loading.Remove();
            if(response.status == 'info') {
                Notiflix.Notify.Success('Product its Available');
                jQuery('#imeResultInfo').append(response.data);
                jQuery('#imeResultInfo').show();
            }
            if(response.status == 'success') {
                Notiflix.Notify.Success('Product All Ready Sold');
                jQuery('#imeResultInfo').append(response.data);
                jQuery('#imeResultInfo').show();
            }
            if(response.status == 'error') {
                Notiflix.Loading.Remove(300);
                Notiflix.Notify.Warning('Product Not Found');
                jQuery('#imeResultInfo').hide();
            }  
        } else {
            Notiflix.Notify.Failure( 'Data Not Found' );
            Notiflix.Loading.Remove(300);
            //jQuery('#imeResultInfo').hide();
        }

        if(response == 'empty' || response == 'error') {
            //jQuery('.ApiproductId').val(response.ProductID).prop('readonly',false);
            Notiflix.Notify.Info( 'Data Not Found! Please Try Another Product Id..' );
            //jQuery('#imeResultInfo').hide();
        }
        
    }
  });
});
//Get Banner Data
function getBannerData(){
  var url = "banner";
  jQuery('.loading').show();
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"HTMl",
    success:function(response) {
        //Notiflix.Notify.Success( 'Success' );
        //Notiflix.Loading.Remove(200);
        setTimeout(function(){// wait for 5 secs(2)
            window.location.reload(); // then reload the page.(3)
        }, 500);
    },
  });
}
// Add Banner Data
jQuery('#AddBanner').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"banner.add",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response) {
        Notiflix.Loading.Remove(300);
        if(response.errors) {
            if(response.errors.banner_pic){
                jQuery( '.banner-error' ).html( response.errors.banner_pic );
            }
            if(response.errors.status){
                jQuery( '.status-error' ).html( response.errors.status );
            }
        }
        if(response == "error"){
            Notiflix.Notify.Failure('Banner Save Failed');
            Notiflix.Loading.Remove(600);
        }

        if(response == "success") {
            jQuery("#AddBanner")[0].reset();
            return getBannerData();
            Notiflix.Notify.Success('Banner Save Successfull');
            Notiflix.Loading.Remove(600);
        }

        if(response.fail) {
            if(response.errors.name){
                jQuery('#error_field').addClass('has-error');
                jQuery('#error-name').html( response.errors.name[0] );
                Notiflix.Notify.Failure( 'Data Insert Failed' );
                Notiflix.Loading.Remove(600);
            }
        }

    },
    error:function(error){
      Notiflix.Notify.Failure('Banner Save Failed');
      Notiflix.Loading.Remove(600);
    }
  });
});
//Edit Banner
jQuery(document).on("click","#editBannerInfo",function(e){
  e.preventDefault();
  var bannerId  = jQuery(this).data('id');
  var url       = "banner.edit"+"/"+bannerId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response.bannerInfo.status);
        jQuery('#update_id').val(response.bannerInfo.id);
        var imgUrl = '<img src="'+response.bannerInfo.image_path+'" width="100%" height="150px"/>';
        $('#img-tag').html(imgUrl);
        //Notiflix.Notify.Success( 'Data Get Successfull' );
        //Notiflix.Loading.Remove(600);
        if (response.bannerInfo.status == 1){
            jQuery("#option1").prop("checked", true);
        } else {
            jQuery("#option2").prop("checked", true);
        }

        if(response.bannerInfo.banner_for == 'all') {
            jQuery(".uall").prop("selected", true);
        } else if (response.bannerInfo.banner_for == 'bp') {
            jQuery(".ubp").prop("selected", true);
        } else {
            jQuery(".uretailer").prop("selected", true);
        }
    }
  });
});
//Update Banner
jQuery('#UpdateBanner').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    var formData = new FormData(this);
    formData.append('_method', 'post');
  
    var UpdateId   = jQuery('#update_id').val();
    var data       = jQuery("#UpdateBanner").serialize();
    jQuery.ajax({
        url:"banner.update",
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            Notiflix.Loading.Arrows('Data Processing');
        },
        success:function(response) {
            Notiflix.Loading.Remove(300);
            if(response == "success") {
                Notiflix.Notify.Success('Data Update Successfull');
                jQuery("#UpdateBanner")[0].reset();
                Notiflix.Loading.Remove(600);
                return getBannerData();
            }
        },
        error:function(error) {
          Notiflix.Notify.Failure('Data Update Failed');
        }
    });
});
//Banner Modal Status
jQuery('.banner-toggle-class').change(function(e) {
    e.preventDefault();
    var status = jQuery(this).prop('checked') == true ? 1 : 0; 
    var updateId = jQuery(this).data('id');
    var url = "banner.status"+"/"+updateId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response.success) {
                Notiflix.Notify.Success( 'Data Update Successfull' );
                Notiflix.Loading.Remove(600);
            }
        
            if(response.error) {
                Notiflix.Notify.Failure( 'Data Update Failed' );
                Notiflix.Loading.Remove(600);
            }

        }
    });
});
jQuery('.pending-order-toggle-class').change(function(e) {
e.preventDefault();
    var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
    var orderId     = jQuery(this).data('id');
    var url         = "PendingOrderStatus"+"/"+orderId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response.success) {
                Notiflix.Notify.Success( 'Order Status Update Successfull' );
            }
            if(response.error) {
                Notiflix.Notify.Failure( 'Order Status Update Failed' );
            }

        }
    });
});
// Sales Product Model Details Data View
jQuery(document).on("click","#viewProductSalesDetails",function(e){
  e.preventDefault();
  $('#itemList').html("");
  var modelNumber = jQuery(this).data('id');
  var url = "productSalesReportDetails"+"/"+modelNumber;
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
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#itemList').append(response.itemList);
        }
    }
  });
});
// Seller Sales Product Model Details Data View
jQuery(document).on("click","#SellerProductSalesDetails",function(e){
  e.preventDefault();
  $('#itemList').html("");
  var modelSellerId = jQuery(this).data('id');
  var url = "sellerProductSalesReport"+"/"+modelSellerId;
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
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#itemList').append(response.itemList);
        }
    }
  });
});
//Edit imei dispute number
jQuery(document).on("click","#editIMEIinfo",function(e){
  e.preventDefault();
  jQuery('#UpdateIMEIDispute').html();
  var imeiDisputeId  = jQuery(this).data('id');
  var url            = "imei.dispute-edit"+"/"+imeiDisputeId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response.imeidisputeInfo.imei_number);
        if(response.status == 'success')
        {
            jQuery('.imeidisputeId').val(response.imeidisputeInfo.id);
            jQuery('.imeiNumber').val(response.imeidisputeInfo.imei_number);
        }
         
    }
  });
});
//Update imei dispute number
jQuery('#UpdateIMEIDispute').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"imei.dispute-reply",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response == 'success') {
            Notiflix.Notify.Success( 'Data Update Successfull' );
            Notiflix.Loading.Remove(300);
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response == 'error') {
            Notiflix.Notify.Warning( 'Data Update Failed.Please Try Again' );
            Notiflix.Loading.Remove(300);
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Data Insert Failed' );
      Notiflix.Loading.Remove(300);
    }
  });
});
//User Status Active and InActive
jQuery('.user-toggle-class').change(function(e) {
    e.preventDefault();
    var status      = jQuery(this).prop('checked') == true ? 1 : 0; 
    var userId     = jQuery(this).data('id');
    var url         = "UserStatus"+"/"+userId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            console.log(response);
            if(response.success) {
                Notiflix.Notify.Success( 'Data Update Successfull' );
            }
            if(response.error) {
                Notiflix.Notify.Failure( 'Data Update Failed' );
            }

        }
    });
});
</script>
<script type="text/javascript">
// CSRF Token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
jQuery(document).ready(function() {
    jQuery( "#bp_search" ).autocomplete({
        source: function( request, response ) {
            // Fetch data
            jQuery.ajax({
                url: "{{url('bp_search')}}",
                method:"GET",
                dataType: "json",
                data: {
                   _token: CSRF_TOKEN,
                   search: request.term
                },
                success: function(data){
                  console.log(data);
                  response(data);
                }
            });
        },
        select: function (event, ui) {
            // Set selection
            $('#bp_search').val(ui.item.label); // display the selected text
            $('#bp_id').val(ui.item.value); // save selected id to input
            return false;
        }
    });

    jQuery( "#retailer_search" ).autocomplete({
        source: function( request, response ) {
            // Fetch data
            jQuery.ajax({
                url: "{{url('retailer_search')}}",
                method:"GET",
                dataType: "json",
                data: {
                   _token: CSRF_TOKEN,
                   search: request.term
                },
                success: function(data){
                  console.log(data);
                  response(data);
                }
            });
        },
        select: function (event, ui) {
            // Set selection
            $('#retailer_search').val(ui.item.label); // display the selected text
            $('#retailer_id').val(ui.item.value); // save selected id to input
            $('#retailer_name').val(ui.item.retailer_name); // save selected id to input
            return false;
        }
    });
});
</script>

<script type="text/javascript">
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
jQuery(document).on('change','#zone',function(){
    var zoneIds = $(this).val();
    //alert(zoneIds);
    jQuery.ajax({
        url: "{{url('searchRetailer')}}",
        method:"GET",
        dataType: "json",
        data: {
            _token: CSRF_TOKEN,
            search: zoneIds
        },
        success: function(data){
            console.log(data);
            jQuery('#retailerList').append(data);
        }
    });
});
// Pre Booking Order  Model Details Data View
jQuery(document).on("click","#viewOrderSalesDetails",function(e){
  e.preventDefault();
  $('#orderList').html("");
  var getModel = jQuery(this).data('id');
  var url = "preOrderReportDetails"+"/"+getModel;
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
        if(response == "error"){
            Notiflix.Notify.Failure( 'Data Not Found' );
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 1000);
        }

        if(response) {
            jQuery('#orderList').append(response.itemList);
        }
    }
  });
});
//Employee On Change Function Start
jQuery(document).on('change','.empId',function(){
    var empId = $(this).val();
    var url   = "getEmployeeInfo"+"/"+empId
    jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response);
        if(response.name) {
            jQuery('.uname').val(response.name);
        }
        if(response.email) {
            jQuery('.uemail').val(response.email);
        }
    }
  });
});
//Pending or Bounce Order Status Change
jQuery(document).on("click","#updateOrderStatus",function(e){
    e.preventDefault();
    $('#orderId').val(0);
    var orderId = jQuery(this).data('id');
    $('#orderId').val(orderId);
});
jQuery(document).on('change','#pendingOrderStatus',function(){
    var status = $(this).val();
    $('.commentsBox').fadeOut('200');
    if(status == 1) {
        $('.commentsBox').fadeIn('200');
    }
});
jQuery('#UpdatePendingOrder').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"pendingOrderStatusUpdate",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response == 'success') {
            Notiflix.Notify.Success('Order Status Changed Successfully');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 200);
        }

        if(response == 'error') {
            Notiflix.Notify.Warning( 'Order Status Changed Failed' );
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
    }
  });
});


// BP Order Details Data View
jQuery(document).on("click","#viewBpOrderDetails",function(e) {
    e.preventDefault();
    $('#salesInfo').html("");
    $('#itemList').html("");
    var bpId = jQuery(this).data('id');
    var url = "BpOrderDetailsView"+"/"+bpId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            console.log(response);
            Notiflix.Loading.Remove(300);

            if(response == "error") {
                Notiflix.Notify.Failure( 'Data Not Found' );
                setTimeout(function() {// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 1000);
            }

            if(response) {
                jQuery('#salesInfo').append(response.salesInfo);
                jQuery('#itemList').append(response.itemList);
            }
        }
    });
});
/*
jQuery(document).on('change','#bpSalesOrdering',function(e) {
    e.preventDefault();
    var ordbY = $(this).val();
    var url     = "bpSalesReportForm"+"/"+ordbY
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            //console.log(response);
            window.location.reload();
        }
    });
});
*/
jQuery(document).on("click","#setRetailerWorkingHour",function(e){
    e.preventDefault();
    $('#retailerId').val(0);
    var retailId = jQuery(this).data('id');
    var url     = "retailer.open_working_time_modal"+"/"+retailId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            console.log(response.startTime);
            $('.startTime').val(response.startTime);
            $('.endTime').val(response.endTime);
            $('#retailerId').val(retailId);
        }
    });
});
jQuery('#saveShopWorkingTime').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"retailer.save_working_time",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response == 'success') {
            Notiflix.Notify.Success('Workign Time Save Successfully');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 200);
        }

        if(response == 'error') {
            Notiflix.Notify.Warning( 'Workign Time Save Failed' );
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
    }
  });
});
//Product Stock Maintain Modal Start
jQuery(document).on("click","#productStock",function(e){
    e.preventDefault();
    $('#productId').val(0);
    var productId = jQuery(this).data('id');
    var url     = "productStockEdit"+"/"+productId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:"JSON",
        success:function(response) {
            console.log(response);
            $('#default_qty').val(response.default_qty);
            $('#yeallow_qty').val(response.yeallow_qty);
            $('#red_qty').val(response.red_qty);
            $('#productId').val(productId);
        }
    });
});
jQuery('#saveProductStockMaintain').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url:"saveProductStockMaintain",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response == 'success') {
            Notiflix.Notify.Success('Save Successfully');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 200);
        }

        if(response == 'error') {
            Notiflix.Notify.Warning( 'Save Failed' );
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
    }
  });
});
//Get Current Stock By Dealer,Retailer & Employee
jQuery(document).on('change','#clientType',function(){
    $('.searchId').html("");
    var getType = $(this).val();
    if(getType == 'retailer') {
        $html = '<label>Retailer Phone</label>&nbsp;<span class="required">*</span><input type="text" name="search_id" class="form-control" placeholder="Ex: 01796452391" min="11" required>';

        $('.searchId').append($html);
    }
    else if(getType == 'dealer') {
        $html = '<label>Dealer Code / Phone Number</label>&nbsp;<span class="required">*</span><input type="text" name="search_id" class="form-control" placeholder="Ex: 58396 / 01676053537" min="3" required>';

        $('.searchId').append($html);
    }
    if(getType == 'emp') {
        $html = '<label>Employee ID</label>&nbsp;<span class="required">*</span><input type="text" name="search_id" class="form-control" placeholder="Ex: 14909" min="3" required>';

        $('.searchId').append($html);
    }
});
/*
jQuery(document).on("click","#details",function(e){
    e.preventDefault();
    Notiflix.Loading.Arrows('Sending...');
    Notiflix.Loading.Remove(500);
});
jQuery('#details').submit(function(e){
    e.preventDefault();
    alert('Hi');
    jQuery.ajax({
        url:"get_stock",
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(){
            jQuery("#loader").show();
            Notiflix.Loading.Arrows('Sending...');
        },
        success:function(response) {
            console.log(response);
        },
        error:function(error){
          Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
        }
    });
});
jQuery('#summary').submit(function(e){
    e.preventDefault();
    jQuery.ajax({
        url:"get_stock",
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            Notiflix.Loading.Arrows('Sending...');
        },
        success:function(response) {
            console.log(response);
        },
        error:function(error){
          Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
        }
    });
});
*/
function getStockType($type=null) {
    if($type == 1) {
        $('.resultType').val($type);
    } else {
        $('.resultType').val(2);
    }
}
jQuery("form#stockSearch").on('submit', function(event){
    jQuery.ajax({
        url:"get_stock",
        method:"POST",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        error:function(error){
          Notiflix.Loading.Arrows('Request Sending...');
        }
    });
});
//Menu Module Start
jQuery('#AddMenu').submit(function(e){
  e.preventDefault();
  jQuery.ajax({
    url: "{{ route('menu.save') }}",
    method:"POST",
    data:new FormData(this),
    dataType:'JSON',
    contentType: false,
    cache: false,
    processData: false,
    success:function(response){
        if(response == 'success') {
            jQuery("#AddMenu")[0].reset();
            Notiflix.Notify.Success('Menu Save Successfully');
            setTimeout(function(){// wait for 5 secs(2)
                window.location.reload(); // then reload the page.(3)
                $(".btnCloseModal").click();
            }, 200);
        }

        if(response == 'error') {
            Notiflix.Notify.Warning( 'Menu Save Failed' );
        }
    },
    error:function(error){
      Notiflix.Notify.Failure( 'Something Went Wrong.Please Try Again' );
    }
  });
});
jQuery('.menu-toggle-class').change(function(e) {
    e.preventDefault();
    var status = jQuery(this).prop('checked') == true ? 1 : 0; 
    var menuId = jQuery(this).data('id');
    var url = "changeStatus"+"/"+menuId;
    jQuery.ajax({
        url:url,
        type:"GET",
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response=='success'){
                Notiflix.Notify.Success('Menu Update Successfully' );
            }
        
            if(response=='error'){
                Notiflix.Notify.Failure('Menu Update Failed.Please Try Again');
            }
        }
    });
});
// Edit  Data
jQuery(document).on("click","#editmenu",function(e){
  e.preventDefault();
  var menuId    = jQuery(this).data('id');
  var url       = "editMenu"+"/"+menuId;
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    beforeSend: function() {
        Notiflix.Loading.Arrows('Data Processing');
    },
    success:function(response) {
        console.log(response);
        Notiflix.Loading.Remove(300);
        jQuery('#update_id').val(response.id);
        jQuery('.menuName').val(response.menu_name);
        jQuery('.menuLink').val(response.menu_link);
        jQuery('.orderBy').val(response.order_by);

        if (response.status == 1) {
            jQuery("#option1").prop("checked", true);
        } else {
            jQuery("#option2").prop("checked", true);
        }

        if(response.parent_menu !=null) {
            jQuery(".parentMenuId"+response.parent_menu).prop("selected", true);
        }

        if(response.orderBy !=null) {
            jQuery(".orderBy").prop("selected", true);
        }
    }
  });
});
// Update Data
jQuery('#UpdateMenu').on("submit", function(arg){
    jQuery.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    arg.preventDefault();
    var formData = new FormData(this);
    formData.append('_method', 'post');
    var menuId   = jQuery('#update_id').val();
    var data     = jQuery("#UpdateMenu").serialize();
    jQuery.ajax({
        url:"updateMenu",
        type:"POST",
        data:formData,
        dataType:'JSON',
        cache: false,
        contentType: false,
        processData: false,
        success:function(response){
            if(response == "success"){
                jQuery("#UpdateMenu")[0].reset();
                Notiflix.Notify.Success('Menu Update Successfully');
                setTimeout(function(){// wait for 5 secs(2)
                    window.location.reload(); // then reload the page.(3)
                    $(".btnCloseModal").click();
                }, 200);
            }
        
            if(response == "error"){
                Notiflix.Notify.Failure( 'Data Update Failed' );
            }
        },
        error:function(error) {
          jQuery("#UpdateMenu")[0].reset();
          Notiflix.Notify.Failure( 'Data Update Failed' );
        }
    });
});
//Menu Module End
// Listen for click on toggle checkbox
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});
$('.checkParentMenuId').click(function(event) {
    var menuParentId  = jQuery(this).data('id');  
    if(this.checked) {
        // Iterate each checkbox
        $('.childParentId_'+menuParentId).each(function() {
            this.checked = true;                        
        });
    } else {
        $('.childParentId_'+menuParentId).each(function() {
            this.checked = false;                       
        });
    }
});
//BP or Retail Category On Change Function Start
jQuery(document).on("change","#category_id",function(e){
  e.preventDefault();
  var catId = $(this).val();
  var url   = "bpromoter.focus_model_to_bp_by_cat"+"/"+catId
  jQuery.ajax({
    url:url,
    type:"GET",
    dataType:"JSON",
    success:function(response) {
        console.log(response);
        $('.catId').val(response.catId);
        window.location.reload();
    },
    error:function(error) {
      //window.location.reload();
    }
  });
});
</script>  
<!--Multi Chart js Integration Start Here-->
<script src="{{asset('public/admin/js/highcharts/highcharts.js')}}"></script>
<script src="{{asset('public/admin/js/highcharts/exporting.js')}}"></script>
<script src="{{asset('public/admin/js/highcharts/export-data.js')}}"></script>
<script src="{{asset('public/admin/js/highcharts/accessibility.js')}}"></script>
<!-- Daily Sales Report Chart Start -->
<script type="text/javascript">
var salesQty =  <?php if(isset($salesQty)) { echo $salesQty; } ?>;
Highcharts.chart('monthlySalesChart', {
    title: {
        text: 'Daily Sales Report'
    },

     xAxis: {
        categories: <?php if(isset($salesDate)) { echo $salesDate; } ?>
    },

    yAxis: {
        title: {
            text: 'Number of Sales Quantity'
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            allowPointSelect: true
        }
    },

    series: [{
        name: 'Quantity',
        data: salesQty
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }
});
</script>
<!-- Daily Sales Report Chart End -->

<!-- BP Top 10 Saler Report Chart Start -->
<script>
var ctx = document.getElementById('bpTopSaller').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php if(isset($bpName)) { echo $bpName; } ?>, //['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# Amount',
            data: <?php if(isset($bpAmount)) { echo $bpAmount; } ?>, //[12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<!-- BP Top 10 Saler Report Chart End -->

<!-- BP Last 10 Saler Report Chart Start -->
<script>
var ctx = document.getElementById('bpLastSaller').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php if(isset($LastbpName)) { echo $LastbpName; } ?>, //['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# Amount',
            data: <?php if(isset($LastbpAmount)) { echo $LastbpAmount; } ?>, //[12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<!-- BP Last 10 Saler Report Chart End -->

<!-- Retailer Top 15 Saler Report Chart Start -->
<script>
var ctx = document.getElementById('retailerTopSaller').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php if(isset($retailerName)) { echo $retailerName; } ?>,//["Mahim Telecom","Lea Moni Telecom","Provati Mobile Zone","Hridoy Telecom","Arman Telecom"],
        datasets: [{
            label: '# Amount',
            data: <?php if(isset($retailerAmount)) { echo $retailerAmount; } ?>, //[12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 71, 0.5)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<!-- Retailer Top 15 Saler Report Chart End -->

<!-- Model Waise Top 15 Sales Report Chart Start -->
<script src="{{asset('public/admin/js/charts/loader.js')}}"></script>
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Month Name', 'Registered User Count'],
        @php
        if(isset($modelWaiseSalesList)) {
            foreach($modelWaiseSalesList as $d) {
                echo "['".$d->product_model."', ".$d->totQty."],";
            }
        }
        @endphp
    ]);
    var options = {
        title: 'Model Waise Sales Quantity',
        is3D: false,
    };
    var chart = new google.visualization.PieChart(document.getElementById('modelWaiseSalesChart'));
    chart.draw(data, options);
}
</script>
<!-- Model Waise Top 15 Sales Report Chart End -->

<!-- Monthly Sales Report Chart Start -->
<script type="text/javascript">
var monthSalesQty =  <?php if(isset($yearMonthQty)) { echo $yearMonthQty; } ?>;
Highcharts.chart('yearMonthlySalesList', {
    title: {
        text: 'Monthly Sales Report'
    },

     xAxis: {
        categories: <?php if(isset($yearMonthNameList)) { echo $yearMonthNameList; } ?>
    },

    yAxis: {
        title: {
            text: 'Monthly Number of Sales Quantity'
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            allowPointSelect: true
        }
    },

    series: [{
        name: 'Quantity',
        data: monthSalesQty
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }
});
</script>
<!-- Monthly Sales Report Chart End -->

<!-- Application Log Report Chart Start -->
<script type="text/javascript">
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Error Name', 'Execute Point'],
        @php
        if(isset($errorLoglist)) {
            foreach($errorLoglist as $d) {
                echo "['".$d['name']."', ".$d['qty']."],";
            }
        }
        @endphp
    ]);
    var options = {
        title: 'Application Log Report',
        is3D: false,
    };
    var chart = new google.visualization.PieChart(document.getElementById('logReportChart'));
    chart.draw(data, options);
}
</script>
<!-- Application Log Report Chart End -->
<!--Multi Chart js Integration End Here-->
<!-- Script -->
@yield('js')