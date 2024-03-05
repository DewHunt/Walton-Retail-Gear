<?php $segment = Request::segment(1); ?>

<a href="{{ route('report.bp-sales') }}" class="list-group-item {{ $segment == 'report.bp-sales' || $segment == 'bpDateRangesalesReport' || $segment == 'bpSaleOrderDetails' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    BP Sales Reports
</a>

<a href="{{ route('report.sales-invoice') }}" class="list-group-item {{ $segment == 'report.sales-invoice' || $segment == 'dateRangesalesReport' || $segment == 'SaleOrderDetails' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    Sales Invoice
</a>

<a href="{{ route('report.incentive') }}" class="list-group-item {{ $segment == 'report.incentive' || $segment == 'incentive_report' || $segment == 'SaleIncentiveDetails' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    Incentive Reports
</a>

<a href="{{ route('report.bp-attendance') }}" class="list-group-item {{ $segment == 'report.bp-attendance' || $segment == 'bp_attendance_report' || $segment == 'bpAttendanceDetails' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    BP Attendance Reports
</a>

<a href="{{ route('report.bp-leave') }}" class="list-group-item {{ $segment == 'report.bp-leave' || $segment == 'bp_leave_report' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    BP Leave Reports
</a>

<a href="{{ route('report.imei-sold') }}" class="list-group-item {{ $segment == 'report.imei-sold' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    IMEI Sold Reports
</a>

<a href="{{ route('report.sales-product') }}" class="list-group-item {{ $segment == 'report.sales-product' ||  $segment == 'modelSalesReport' ||  $segment == 'modelSalesReportDetails' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    Product Sales Report
</a>
<a href="{{ route('report.pre-booking') }}" class="list-group-item {{ $segment == 'report.pre-booking' || $segment == 'preBookingReport' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    Pre-Booking Order Report
</a>
<a href="{{ route('report.pending-bounce-order') }}" class="list-group-item {{ $segment == 'report.pending-bounce-order' || $segment == 'pendingOrderReport' ? 'active':'inactive' }}">
    <span class="icon-holder">
        <i class="c-light-blue-000 fa fa-check-square"></i>
    </span>
    Pending Or Bounce Order
</a>