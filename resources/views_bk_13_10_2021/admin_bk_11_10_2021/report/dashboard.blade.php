@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Report Dashboard</h4>
<div class="row">
    <div class="masonry-item col-md-3 mY-10">
        <div class="bd bgc-white">
            <div class="layers">
                <div class="layer w-100">
                    <div class="list-group">
                        @include('admin.report.report_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="masonry-item col-md-9 mY-10">
        <div class="bgc-white p-20 bd">
           <div style="border-bottom: 1px solid black;">
                <h4 class="c-grey-900 text-center">Report Showing Goes To Here...</h4>
            </div>
        </div>
    </div>
</div>
@endsection