@extends('admin.master.master')
@section('content')
<h4 class="c-grey-900 mB-20">Retail Employee List</h4>
<table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Sl.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Last Seen</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Sl.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Last Seen</th>
        </tr>
    </tfoot>
    <tbody>
        @if(isset($UserStatus))
            @foreach($UserStatus as $row)
            <tr>
                <td>{{ $loop->index++ }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td>
                    @if(Cache::has('is_online' . $row->id))
                        <span class="text-success">Online</span>
                    @else
                        <span class="text-secondary">Offline</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($row->last_seen)->diffForHumans() }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection