<!DOCTYPE html>
<html>
<head>
  <title>Laravel Live Data Search with Sorting & Pagination using Ajax</title>
  <link href="{{ asset('public/admin/css/jquery-ui.css') }}" rel="stylesheet">
  <link href="{{asset('public/admin/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  {{--   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="{{asset('public/admin/js/bootstrap-toggle.min.js')}}"></script> --}}
</head>
<body>
<br />
<div class="container">
  <h3 align="center">Laravel Live Data Search with Sorting & Pagination using Ajax</h3><br />
  <div class="row">
    <div class="col-md-9"></div>
    <div class="col-md-3">
      <div class="form-group">
        <input type="text" name="serach" id="serach" class="form-control" />
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer">ID <span id="id_icon"></span></th>
          <th class="sorting" data-sorting_type="asc" data-column_name="dealer_code" style="cursor: pointer">Dealer Code </th>
          <th class="sorting" data-sorting_type="asc" data-column_name="dealer_name" style="cursor: pointer">Dealer Name </th>
          <th class="sorting" data-sorting_type="asc" data-column_name="zone" style="cursor: pointer">Zone </th>
          <th class="sorting" data-sorting_type="asc" data-column_name="dealer_phone_number" style="cursor: pointer">Phone </th>
          <th class="sorting" data-sorting_type="asc" data-column_name="dealer_status" style="cursor: pointer">Status </th>
           <th>Action </th>
        </tr>
      </thead>
      <tbody>
        @include('pagination_data')
      </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
  </div>
</div>
</body>
</html>

<script type="text/javascript" src="{{asset('public/admin/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/vendor.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bundle.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/beacon.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/admin/js/notiflix-1.9.1.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="{{asset('public/admin/js/bootstrap-toggle.min.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('public/admin/js/jquery-ui.js') }}"></script>
<script src="{{ asset('public/admin/js/bootstrap-datepicker.min.js') }}"></script>
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
      autoclose: true
    });
    jQuery('#example').DataTable();
}); 
</script>

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
      jQuery('.toggle').each(function() {
        $(this).toggles({
           on: $(this).data('toggle-on')
        });
      });
    }
  })
}


$(document).ready(function() {
  $(document).on('keyup', '#serach', function() {
    var query = $('#serach').val();
    var column_name = $('#hidden_column_name').val();
    var sort_type = $('#hidden_sort_type').val();
    var page = $('#hidden_page').val();
    fetch_data(page, sort_type, column_name, query);
  });

  $(document).on('click', '.sorting', function(){
    var column_name = $(this).data('column_name');
    var order_type = $(this).data('sorting_type');
    var reverse_order = '';
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
    var page = $('#hidden_page').val();
    var query = $('#serach').val();
    fetch_data(page, reverse_order, column_name, query);
  });

  $(document).on('click', '.pagination a', function(event){
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $('#hidden_page').val(page);
    var column_name = $('#hidden_column_name').val();
    var sort_type = $('#hidden_sort_type').val();

    var query = $('#serach').val();

    $('li').removeClass('active');
    $(this).parent().addClass('active');
    $(this).data('toggle-on', true);
    fetch_data(page, sort_type, column_name, query);
  });
});
</script>

<script>
        jQuery(function(){
          jQuery.ajaxSetup({
            headers: { 'X-CSRF-Token' : '{{csrf_token()}}' }
          });
        });
        </script>
        

        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/dealer-information.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/product-information.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/employee-information.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/zone-information.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/retailer-information.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/brand-promoter-information.js')}}"></script>
        <script type="text/javascript" src="{{asset('public/admin/js/custom-js/user-information.js')}}"></script>
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
