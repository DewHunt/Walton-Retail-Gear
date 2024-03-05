@if(isset($BrandPromoterList))
    @foreach($BrandPromoterList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->bp_name }}</td>
        <td>{{ $row->bp_phone }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="promoter-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <button type="button" data-id="{{ $row->id }}" id="editBPromoterInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editBPromoterModal">Edit</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="5" align="center">{!! $BrandPromoterList->links() !!}</td></tr>
@endif
    


                    