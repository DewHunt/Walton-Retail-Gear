@extends('admin.master.master')
@section('content')
<div class="masonry-sizer col-md-6"></div>
<div class="masonry-item col-md-6">
    <div class="bgc-white p-20 bd">
        <h6 class="c-grey-900">Employee Update Form</h6>
        <div class="mT-30">
            <form>
            	<div class="form-group">
                    <label>Name</label><input type="text" class="form-control" placeholder="Enter name" />
                </div>
                <div class="form-group">
                    <label>Mobile</label><input type="text" class="form-control"placeholder="Enter mobile number" />
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection