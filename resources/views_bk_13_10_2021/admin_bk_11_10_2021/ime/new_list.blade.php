@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">IMEI Information</h4>

<div class="bgc-white p-20 bd">
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ImeiCheckModal" style="margin-left: 5px">Check IMEI</button>
</div>

<div class="row" style="display:none">
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" name="serach" id="serach" class="form-control" />
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive" style="display:none">
	<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="email_address" style="cursor: pointer;">IMEI Number</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="mobile_no" style="cursor: pointer;">Alternet IMEI</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="zone" style="cursor: pointer;">Dealer Name</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="distributor_name" style="cursor: pointer;">Model</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="district" style="cursor: pointer;">Color</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="code" style="cursor: pointer;">Code</th>
        </tr>
    </thead>
    <tbody>
    	<!-- @include('admin.ime.new_result_data') -->
    </tbody>
</table>

<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<div class="modal fade" id="ImeiCheckModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">IMEI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="CheckImei">
            @csrf
            <div class="modal-body">
                <div class="form-row" id="ApiSearchDiv">
                    <div class="form-group col-md-10">
                        <input type="text" class="form-control" id="search_ime" placeholder="Ex: 351871111291778" maxlength="15">
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" class="btn btn-primary" id="search_imei_button">Search</button>
                    </div>
                </div>

                <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" id="imeResultInfo">
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button> 
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

                    