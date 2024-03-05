@extends('admin.master.master')
@section('content')
<div class="masonry-item col-md-12">
    
    <div class="bgc-white p-20 bd">
        <h6 class="c-grey-900">Update Message
            <a href="{{ route('message.index') }}">
                <button  type="button" class="btn btn-primary pull-right btn-sm" style="margin-left: 5px">Message List</button>
            </a>
        </h6>
        <p style="font-size:12px"><b>Note</b>: All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.</p>

        <div class="mT-30">
            <form role="form" method="post" action="{{url('update_message',\Crypt::encrypt($messageInfo->id))}}">
            <input type="hidden" name="_method" value="put" />
            @csrf
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>BP</label>
                    <input type="text" id="bp_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone" value="{{ $bpName }}" />
                    <input type="hidden" id='bp_id' name="bp_id" value="{{ $messageInfo->bp_id }}" class="form-control"readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Retailer</label>
                    <input type="text" id="retailer_search" class="form-control ui-autocomplete-input" placeholder="Search By Name or Phone" value="{{ $retailerName }}"/>
                    <input type="hidden" id='retailer_id' name="retailer_id" value="{{ $messageInfo->retailer_id }}" class="form-control"readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Message <span class="required">*</span></label>
                    <textarea class="form-control" name="message" required="">{{ $messageInfo->message}}</textarea>
                    <span class="text-danger">{{ $errors->first('message') }}</span>
                </div>
            </div>

            

            {{-- 
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Status <span class="required">*</span></label>
                <div class="col-sm-6">
                    <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                    <label><input type="radio" name="status" value="0"> In-Active</label>
                </div>
            </div> 
            --}}

            <div class="form-group row">
                <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary pull-ledt">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection