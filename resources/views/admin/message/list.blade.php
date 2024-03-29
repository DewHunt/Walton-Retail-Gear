@extends('admin.master.master')
@section('content')
<style>
    .cp {
        padding:5px
    }
    .csearch {
        width:285px;
    }           
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
    }
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
    }
</style>
<h4 class="c-grey-900 mB-20">Message List</h4>
<div class="col-md-12 cp">
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="form-group top-margin">
                <input type="text" name="serach" id="serach" class="form-control pull-right csearch"/>
            </div>
        </div>
    </div>
</div>
<div id="tag_container" class="table-responsive">
    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="sorting" data-sorting_type="desc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th class="sorting" data-sorting_type="desc" data-column_name="message" style="cursor: pointer;">Message</th>
                <th class="sorting" data-sorting_type="desc" data-column_name="date_time" style="cursor: pointer;">Date & Time</th>
                <th class="sorting" style="cursor: pointer;">Details</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.message.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
</div>

<style type="text/css">
    .text-small {
        font-size: 0.9rem;
    }

    .messages-box,
    .chat-box {
        height: 350px;
        overflow-y: scroll;
    }

    .rounded-lg {
        border-radius: 0.5rem;
    }

    input::placeholder {
        font-size: 0.9rem;
        color: #999;
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        .table-responsive .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
        }
        .cvbtn {
            width: 210px !important;
            height: 65px !important;
        }
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3){
        .table-responsive .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
        }
        .cvbtn {
            width: 210px !important;
            height: 65px !important;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .table-responsive .btn {
            padding: 1rem !important;
            font-size: 1.5rem !important;
        }
        .cvbtn {
            width: 210px !important;
            height: 65px !important;
        }
    }
</style>


<!--View Product Modal Start -->
<div class="modal fade" id="viewMessageDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Chat Box-->
                            <div class="col-12 px-0">
                                <div class="chat-box bg-white">
                                    <div id="sendMessage"></div>
                                    <div id="replyMessage"></div>
                                </div>
                            </div>
                            <form class="form-horizontal" method="POST" action="" id="ReplyMessage">
                                @csrf
                                <div class="form-row" style="margin-top:10px">
                                    <div class="form-group col-md-10">
                                        <input type="text" class="form-control" id="reply_message" name="reply_message">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input type="hidden" name="reply_id" id="replyId" readonly="">
                                        <input type="hidden" name="message_id" id="messageId" readonly="">
                                        <button type="submit" class="btn btn-primary btn-block message-table">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button>  
            </div>
        </div>
    </div>
</div>
<!--View Product Modal End -->
@endsection