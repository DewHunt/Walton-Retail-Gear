@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Rsm Information</h4>
<table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Sl.</th>
            <th>Rsm</th>
            <th>Asm</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Zone</th>
            <th>Distributor Name</th>
            <th>District</th>
            <th>Code</th>
            <th>Import Code</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Sl.</th>
             <th>Rsm</th>
            <th>Asm</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Zone</th>
            <th>Distributor Name</th>
            <th>District</th>
            <th>Code</th>
            <th>Import Code</th>
        </tr>
    </tfoot>
    <tbody>
        @if(isset($Rsmlist))
            @foreach($Rsmlist as $row)
            <tr>
                <td>{{ ++$loop->index }}.</td>
                <td>{{ $row->rsm }}</td>
                <td>{{ $row->asm }}</td>
                <td>{{ $row->email_address }}</td>
                <td>{{ $row->mobile_no }}</td>
                <td>{{ $row->zone }}</td>
                <td>{{ $row->distributor_name }}</td>
                <td>{{ $row->district }}</td>
                <td>{{ $row->code }}</td>
                <td>{{ $row->import_code }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection

                    