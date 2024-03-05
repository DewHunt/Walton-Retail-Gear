@extends('admin.master.master')
@section('content')
<style>
    .notification-response{
        display: flex;
        flex-wrap: wrap;
    }
    .success {
        margin-left:10px;
        color: red;
    }
    .failure {
        margin-left:10px;
        color: red;
    }
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
        .select2-container--default .select2-search--inline .select2-search__field{
            width: auto !important;
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
        .select2-container--default .select2-search--inline .select2-search__field{
            width: auto !important;
        }
    }
    @media (min-width: 768px) and (max-width: 1024px) {
        .cp {
            padding:5px
        }
        .csearch {
            width:300px;
        }
        .select2-container--default .select2-search--inline .select2-search__field{
            width: auto !important;
        }
    }
</style>
<h4 class="c-grey-900 mB-20">Notification List</h4>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <button  type="button" class="btn btn-primary pull-right btn-sm" data-toggle="modal" data-target="#AddPushNotificationModal" style="margin-left:5px;">Add Notification</button>  
        </div>
    </div>
</div>
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
                <th style="width:10px" class="sorting" data-sorting_type="asc" data-column_name="id" style="cursor: pointer;">Sl.</th>
                <th style="width:25px" class="sorting" data-sorting_type="asc" data-column_name="title" style="cursor: pointer;">Title</th>
                <th style="width:30px" class="sorting" data-sorting_type="asc" data-column_name="message" style="cursor: pointer;">Message</th>
                <th style="width:5px" class="sorting" data-sorting_type="asc" data-column_name="status" style="cursor: pointer;">Status</th>
                <th style="width:30px">Action</th>
            </tr>
        </thead>
        <tbody>
            @include('admin.notification.result_data')
        </tbody>
    </table>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
</div>

<!--Add New PreBooking Modal Start -->
<div class="modal fade" id="AddPushNotificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Notification</h5>
                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="{{route('pushNotification.add')}}" id="AddPushNotification">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control" required=""/>
                            <span class="text-danger">
                                <strong id="title-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Message <span class="required">*</span></label>
                            <textarea name="message" class="form-control" required="" cols="3" rows="2"></textarea>
                            <span class="text-danger">
                                <strong id="message-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Zone</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" name="zone[]" required="">
                                <option value="">Select Zone</option>
                                <option value="all">All</option>
                                @if(isset($zoneList))
                                @foreach($zoneList as $row)
                                <option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Category</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;" id="category" name="category[]" required="">
                                <option value="">Select Category</option>
                                <option value="all">All</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Message Group</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Group" style="width: 100%;" id="message_group" name="message_group[]" required="">
                                <option value="">Select Group</option>
                                <option value="all">All</option>
                                <option value="bp">BP</option>
                                <option value="retailer">Retailer</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-5" style="margin-top: 15px;">
                            <label>Status <span class="required">*</span></label><br/>
                            <label><input type="radio" name="status" checked="checked" value="1"> Active</label>  &nbsp;&nbsp; 
                            <label><input type="radio" name="status" value="0"> In-Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Add New Pre Booking Modal End -->

<!--Edit & Update Modal Start -->
<div class="modal fade" id="editPushNotificationModal" tabindex="-2" role="dialog" aria-labelledby="editPushNotificationModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModal">Update Notification</h5>

                <span style="font-size:12px;margin-top:6px;margin-left:5px">[** All <span style="color:red;">Red</span> Start Sign Data Must Be Fillable.**]</span>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" action="" id="UpdatePushNotification" >
                <input type="hidden" name="_method" value="PUT"/>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control getTitle" required=""/>
                            <span class="text-danger">
                                <strong id="update-title-error"></strong>
                            </span>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Message <span class="required">*</span></label>
                            <textarea name="message" class="form-control getMessage" required="" cols="3" rows="2"></textarea>
                            <span class="text-danger">
                                <strong id="update-message-error"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Zone</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="getZone"  name="zone" required="">
                                <option value="">Select Zone</option>
                                <option value="all">All</option>
                                @if(isset($zoneList))
                                @foreach($zoneList as $row)
                                <option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Category</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;" id="getCategory" name="category[]" required="">
                                <option value="">Select Category</option>
                                <option value="all">All</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Message Group</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Group" style="width: 100%;"  id="getMessageGroup" name="message_group[]" required="">
                                <option value="">Select Group</option>
                                <option value="all">All</option>
                                <option value="bp">BP</option>
                                <option value="retailer">Retailer</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-5" style="margin-top: 15px;">
                            <label>Status <span class="required">*</span></label><br/>
                            <label><input type="radio" id="option1" name="status" value="1"> Active</label>  &nbsp;&nbsp; 
                            <label><input type="radio" id="option2" name="status" value="0"> In-Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update_id" id="updateId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit & Update Modal End -->

<!--Send Modal Start -->
<div class="modal fade" id="getPushNotificationModal" tabindex="-3" role="dialog" aria-labelledby="getPushNotificationModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModal">Send Notification</h5>
                <span id="success"></h2></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <button onclick="startFCM()"
                    class="btn btn-danger btn-flat" style="display:none">Allow notification
            </button>

            <form class="form-horizontal" method="POST" action="" id="SendPushNotification">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2 pushresponse">
                            <div class="notification-response">
                                <h6>Success :</h6> <span class="success"></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2 pushresponse">
                            <div class="notification-response">
                                <h6>Failure :</h6> <span class="failure"></span>
                            </div>
                        </div>


                        <div class="col-md-12 mb-2">
                            <label>Title <span class="required">*</span></label>
                            <input type="text" name="title" class="form-control sendTitle" required=""/>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>Message <span class="required">*</span></label>
                            <textarea name="body" class="form-control sendMessage" required="" cols="3" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="row" style="display:none">
                        <div class="col-md-6 mb-2">
                            <label>Zone</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Zone" data-dropdown-css-class="select2-purple" style="width: 100%;" id="sendZone"  name="zone" required="">
                                <option value="">Select Zone</option>
                                <option value="all">All</option>
                                @if(isset($zoneList))
                                @foreach($zoneList as $row)
                                <option value="{{ $row->zone_name }}">{{ $row->zone_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Category</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Category" style="width: 100%;" id="sendCategory" name="category[]" required="">
                                <option value="">Select Category</option>
                                <option value="all">All</option>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="display:none">
                        <div class="col-md-6 mb-2">
                            <label>Message Group</label>
                            <select class="select2" multiple="multiple" data-placeholder="Select Group" style="width: 100%;"  id="sendMessageGroup" name="message_group[]" required="">
                                <option value="">Select Group</option>
                                <option value="all">All</option>
                                <option value="bp">BP</option>
                                <option value="retailer">Retailer</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="send_id" id="sendId"/>
                    <button type="button" class="btn btn-secondary btnCloseModal" data-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Send Modal End -->


<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-analytics.js"></script>

<script>
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
                var firebaseConfig = {
                    apiKey: "AIzaSyBZJUHDGO9-680krYewIjRTeurrg66cSDI",
                    authDomain: "retailgear-89ee0.firebaseapp.com",
                    projectId: "retailgear-89ee0",
                    storageBucket: "retailgear-89ee0.appspot.com",
                    messagingSenderId: "198099682584",
                    appId: "1:198099682584:web:4319de0585a25515b04a6d",
                    measurementId: "G-6H4PX7NTRH"
                };
// Initialize Firebase
                firebase.initializeApp(firebaseConfig);
                firebase.analytics();

                function startFCM() {
                    messaging
                            .requestPermission()
                            .then(function () {
                                return messaging.getToken()
                            })
                            .then(function (response) {
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    url: '{{ url("storeToken") }}',
                                    type: 'POST',
                                    data: {
                                        token: response
                                    },
                                    dataType: 'JSON',
                                    success: function (response) {
                                        alert('Token stored.');
                                    },
                                    error: function (error) {
                                        console.log(error);
                                        alert(error);
                                    },
                                });

                            }).catch(function (error) {
                        alert(error);
                    });
                }
                /*
                 messaging.onMessage(function (payload) {
                 const title = payload.notification.title;
                 const options = {
                 body: payload.notification.body,
                 icon: payload.notification.icon,
                 };
                 new Notification(title, options);
                 });
                 */
</script>
@endsection

