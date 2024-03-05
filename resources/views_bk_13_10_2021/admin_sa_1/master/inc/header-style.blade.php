<style>
    #loader {
        transition: all 0.3s ease-in-out;
        opacity: 1;
        visibility: visible;
        position: fixed;
        height: 100vh;
        width: 100%;
        background: #fff;
        z-index: 90000;
    }
    #loader.fadeOut {
        opacity: 0;
        visibility: hidden;
    }
    .spinner {
        width: 40px;
        height: 40px;
        position: absolute;
        top: calc(50% - 20px);
        left: calc(50% - 20px);
        background-color: #333;
        border-radius: 100%;
        -webkit-animation: sk-scaleout 1s infinite ease-in-out;
        animation: sk-scaleout 1s infinite ease-in-out;
    }
    .pagination{
        float: right;
    }
    .required{
        color:red;
    }
    .input-group {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: stretch;
        align-items: stretch;
        width: 100%;
    }
    .input-group-append {
        margin-left: -1px;
    }
    .has-error{
        border-color:red;
    }
    .list-group-item .active .c-light-blue-000 .cH-light-blue-000:active {
        color: #000000 !important;
    }
    .c-light-blue-000 .cH-light-blue-000:hover {
        color: #c0c0c0;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0px !important;
        border-radius: 0px !important;
        margin-right: 0px !important;
    }
    .highcharts-credits {
        display: none;
    }

    @-webkit-keyframes sk-scaleout {
        0% {
            -webkit-transform: scale(0);
        }
        100% {
            -webkit-transform: scale(1);
            opacity: 0;
        }
    }
    @keyframes sk-scaleout {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 0;
        }
    }
    .datepicker {
        padding: 7px !important;
    }
    .highcharts-exporting-group {
        display: none !important;
    }
</style>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{asset('public/admin/css/fontawesome.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/admin/css/font-awesome.min.css')}}"/>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap-datepicker.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/css/jquery-ui.css') }}"/>
<link rel="stylesheet" href="{{asset('public/admin/css/bootstrap-toggle.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/admin/css/notiflix-1.9.1.css')}}"/>
<link rel="stylesheet" href="{{asset('public/admin/css/dataTables.bootstrap4.min.css')}}"/>
<link rel="stylesheet" href="{{asset('public/admin/css/style.css')}}"/>



<link rel="stylesheet" href="{{ asset('public/admin/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('public/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

@yield('css')

<style>
    .top-margin {
        margin-top: 1rem;
    }
    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 320px) 
    and (max-device-width: 568px)
    and (-webkit-min-device-pixel-ratio: 2) {
        body {
            font-size: 30px !important;
            color: #000000 !important;
        }
        .sidebar {
            left: -380px;
            width: 380px !important;
        }
        .is-collapsed .sidebar {
            left: 0px !important;
        }
        .sidebar {
            left: -380px !important;
        }
        .sidebar ul {
            margin-top: 5px;
        }
        .sidebar-menu>li>a {
            font-size: 30px !important;
            border: 1px solid #00000054;
            padding: 10px 15px !important;
            margin: 10px 0 !important;
        }
        .sidebar-logo .mobile-toggle {
            font-size: 30px !important;
        }
        .sidebar-menu>li>a .icon-holder {
            font-size: 30px !important;
        }
        .main-content .toggle{
            /*            padding: 2rem 4rem !important;*/
            padding: 1rem 1rem !important;
            width: 260px !important;
            height: 80px !important;
            margin: 5px 0;
            font-size: 2rem;
        }
        .main-content .toggle-on {
            font-size: 2rem !important;
        }
        .main-content .btn-group-sm>.btn, .btn-sm {
            /*            font-size: 1.5rem !important;
                        padding: 0.7rem 0.75rem !important;
                        margin: 5px 0 5px 0;*/
            font-size: 2rem !important;
            padding: 1rem 1rem !important;
            margin: 5px 0 5px 0;
            width: 285px;
        }
        .main-content .toggle-off.btn {
            padding-left: 6px;
            font-size: 2rem;
            height: 80px !important;
            width: 260px !important;
            margin: 5px 0;
        }
        .modal-dialog {
            max-width: 800px !important;
        }
        .modal-footer .btn {
            padding: 1rem 1rem;
            font-size: 2rem;
            width: 260px;
            height: 80px;
        }
        .modal-header h5 {
            font-size: 2rem !important;
        }
        .modal-header h5 span {
            font-size: 20px !important;
        }
        .main-content .c-grey-900 {
            font-size: 2rem !important;
        }
        .main-content .form-group {
            margin-top: 1rem;
        }
        footer span {
            font-size: 2rem;
        }
        .message-table {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
        }
        .modal-header .close {
            font-size: 3rem !important;
        }
        .modal-body .form-control {
            padding: 1rem .75rem !important;
        }
        .modal-body .form-group .btn {
            padding: 1rem .75rem !important;
            font-size: 2rem !important;
        }
        .main-content .form-group .form-control {
            padding: 1rem .75rem !important;
            font-size: 2rem !important;
        }
        .color-input-padd {
            padding: 0px 5px !important;
            margin: 5px 10px;
        }
        .Td-input {
            display: flex;
            /*flex-wrap: wrap;*/
        }
        /*
.table-striped tbody tr {
    background-color: rgba(0,0,0,.05) !important;
}
        */
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 260px;
            height: 80px;
        }
        .masonry-col {
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }
        .bp-add-incentive-3 {
            max-width: 25% !important;
            flex: 0 0 25% !important;
        }
        .bp-add-incentive-9 {
            max-width: 75% !important;
            flex: 0 0 75% !important;
        }
        .bp-add-incentive-9 .select2-container .select2-selection--single{
            height: 60px !important;
        }

        select.form-control:not([size]):not([multiple]) {
            height: calc(2.5rem + 2px) !important;
        }
        .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .select-h .select2-container .select2-selection--single {
            height: 60px !important;
        }
        .select-h select.form-control:not([size]):not([multiple]){
            height: 60px !important;
            padding: 0rem .75rem !important;
            font-size: 1.5rem !important;
        }
        .mobileCheckBox {
            width: 30px;
            height: 30px;
        }
        .form-check-input_mobile {
            position: absolute;
            margin-top: 4px;
            margin-top: 0.80rem;
            margin-left: -20px;
            margin-left: -2.25rem;
        }

    }

    /* Portrait and Landscape */
    @media only screen 
    and (min-device-width: 375px) 
    and (max-device-width: 812px) 
    and (-webkit-min-device-pixel-ratio: 3) { 
        body {
            font-size: 30px !important;
            color: #000000 !important;
        }
        .sidebar {
            left: -380px;
            width: 380px !important;
        }
        .is-collapsed .sidebar {
            left: 0px !important;
        }
        .sidebar {
            left: -380px !important;
        }
        .sidebar ul {
            margin-top: 5px;
        }
        .sidebar-menu>li>a {
            font-size: 30px !important;
            border: 1px solid #00000054;
            padding: 10px 15px !important;
            margin: 10px 0 !important;
        }
        .sidebar-logo .mobile-toggle {
            font-size: 30px !important;
        }
        .sidebar-menu>li>a .icon-holder {
            font-size: 30px !important;
        }
        .main-content .toggle{
            padding: 1rem 1rem !important;
            width: 260px !important;
            height: 80px !important;
            margin: 5px 0;
            font-size: 2rem;
        }
        .main-content .toggle-on {
            font-size: 2rem !important;
        }
        .main-content .btn-group-sm>.btn, .btn-sm {
            /*            font-size: 1.5rem !important;
                        padding: 0.7rem 0.75rem !important;
                        margin: 5px 0 5px 0;*/
            font-size: 2rem !important;
            padding: 1rem 1rem !important;
            margin: 5px 0 5px 0;
            width: 285px;
        }
        .main-content .toggle-off.btn {
            padding-left: 6px;
            font-size: 2rem;
            height: 80px !important;
            width: 260px !important;
            margin: 5px 0;
        }
        .modal-dialog {
            max-width: 800px !important;
        }
        .modal-footer .btn {
            padding: 1rem 1rem;
            font-size: 2rem;
            width: 260px;
            height: 80px;
        }
        .modal-header h5 {
            font-size: 2rem !important;
        }
        .modal-header h5 span {
            font-size: 20px !important;
        }
        .main-content .c-grey-900 {
            font-size: 2rem !important;
        }
        .main-content .form-group {
            margin-top: 1rem;
        }
        footer span {
            font-size: 2rem;
        }
        .message-table {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
        }
        .modal-header .close {
            font-size: 3rem !important;
        }
        .modal-body .form-control {
            padding: 1rem .75rem !important;
        }
        .modal-body .form-group .btn {
            padding: 1rem .75rem !important;
            font-size: 2rem !important;
        }
        .main-content .form-group .form-control {
            padding: 1rem .75rem !important;
            font-size: 2rem !important;
        }
        .color-input-padd {
            padding: 0px 5px !important;
            margin: 5px 10px;
        }
        .Td-input {
            display: flex;
            /*flex-wrap: wrap;*/
        }
        /*
.table-striped tbody tr {
    background-color: rgba(0,0,0,.05) !important;
}
        */
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 260px;
            height: 80px;
        }
        .masonry-col {
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }
        .bp-add-incentive-3 {
            max-width: 25% !important;
            flex: 0 0 25% !important;
        }
        .bp-add-incentive-9 {
            max-width: 75% !important;
            flex: 0 0 75% !important;
        }
        .bp-add-incentive-9 .select2-container .select2-selection--single{
            height: 60px !important;
        }

        select.form-control:not([size]):not([multiple]) {
            height: calc(2.5rem + 2px) !important;
        }
        .header .header-container .nav-left .notifications .dropdown-menu, .header .header-container .nav-right .notifications .dropdown-menu {
            min-width: 400px !important;
            padding: 0;
        }
        .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .select-h .select2-container .select2-selection--single {
            height: 60px !important;
        }
        .select-h select.form-control:not([size]):not([multiple]){
            height: 60px !important; 
            padding: 0rem .75rem !important;
            font-size: 1.5rem !important;
        }
        .mobileCheckBox {
            width: 30px;
            height: 30px;
        }
        .form-check-input_mobile {
            position: absolute;
            margin-top: 4px;
            margin-top: 0.80rem;
            margin-left: -20px;
            margin-left: -2.25rem;
        }

    }
    
    @media (min-width: 768px) and (max-width: 1024px) {
        body {
            font-size: 30px !important;
            color: #000000 !important;
        }
        .sidebar {
            left: -380px;
            width: 380px !important;
        }
        .is-collapsed .sidebar {
            left: 0px !important;
        }
        .sidebar {
            left: -380px !important;
        }
        .sidebar ul {
            margin-top: 5px;
        }
        .sidebar-menu>li>a {
            font-size: 30px !important;
            border: 1px solid #00000054;
            padding: 10px 15px !important;
            margin: 10px 0 !important;
        }
        .sidebar-logo .mobile-toggle {
            font-size: 30px !important;
        }
        .sidebar-menu>li>a .icon-holder {
            font-size: 30px !important;
        }
        .main-content .toggle{
            padding: 1rem 1rem !important;
            width: 260px !important;
            height: 80px !important;
            margin: 5px 0;
            font-size: 2rem;
        }
        .main-content .toggle-on {
            font-size: 2rem !important;
        }
        .main-content .btn-group-sm>.btn, .btn-sm {
            /*            font-size: 1.5rem !important;
                        padding: 0.7rem 0.75rem !important;
                        margin: 5px 0 5px 0;*/
            font-size: 2rem !important;
            padding: 1rem 1rem !important;
            margin: 5px 0 5px 0;
            width: 285px;
        }
        .main-content .toggle-off.btn {
            padding-left: 6px;
            font-size: 2rem;
            height: 80px !important;
            width: 260px !important;
            margin: 5px 0;
        }
        .modal-dialog {
            max-width: 800px !important;
        }
        .modal-footer .btn {
            padding: 1rem 1rem;
            font-size: 2rem;
            width: 260px;
            height: 80px;
        }
        .modal-header h5 {
            font-size: 2rem !important;
        }
        .modal-header h5 span {
            font-size: 20px !important;
        }
        .main-content .c-grey-900 {
            font-size: 2rem !important;
        }
        .main-content .form-group {
            margin-top: 1rem;
        }
        footer span {
            font-size: 2rem;
        }
        .message-table {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
        }
        .modal-header .close {
            font-size: 3rem !important;
        }
        .modal-body .form-control {
            padding: 1rem .75rem !important;
        }
        .modal-body .form-group .btn {
            padding: 1rem .75rem !important;
            font-size: 2rem !important;
        }
        .main-content .form-group .form-control {
            padding: 1rem .75rem !important;
            font-size: 2rem !important;
        }
        .color-input-padd {
            padding: 0px 5px !important;
            margin: 5px 10px;
        }
        .Td-input {
            display: flex;
            /*flex-wrap: wrap;*/
        }
        /*
.table-striped tbody tr {
    background-color: rgba(0,0,0,.05) !important;
}
        */
        .bgc-white .btn {
            padding: 1rem 1rem !important;
            font-size: 2rem !important;
            width: 260px;
            height: 80px;
        }
        .masonry-col {
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }
        .bp-add-incentive-3 {
            max-width: 25% !important;
            flex: 0 0 25% !important;
        }
        .bp-add-incentive-9 {
            max-width: 75% !important;
            flex: 0 0 75% !important;
        }
        .bp-add-incentive-9 .select2-container .select2-selection--single{
            height: 60px !important;
        }

        select.form-control:not([size]):not([multiple]) {
            height: calc(2.5rem + 2px) !important;
        }
        .header .header-container .nav-left .notifications .dropdown-menu, .header .header-container .nav-right .notifications .dropdown-menu {
            min-width: 400px !important;
            padding: 0;
        }
        .retailer-select-h select.form-control:not([size]):not([multiple]) {
            height: calc(3.4rem + 2px) !important;
        }
        .select-h .select2-container .select2-selection--single {
            height: 60px !important;
        }
        .select-h select.form-control:not([size]):not([multiple]){
            height: 60px !important; 
            padding: 0rem .75rem !important;
            font-size: 1.5rem !important;
        }
        .mobileCheckBox {
            width: 30px;
            height: 30px;
        }
        .form-check-input_mobile {
            position: absolute;
            margin-top: 4px;
            margin-top: 0.80rem;
            margin-left: -20px;
            margin-left: -2.25rem;
        }
    }
</style>
