@foreach($data as $row)
<tr>
      <td>{{ $row->id}}.</td>
      <td>{{ $row->dealer_code }}</td>
      <td>{{ $row->dealer_name }}</td>
      <td>{{ $row->zone }}</td>
      <td>{{ $row->dealer_phone_number }}</td>
      <td>
            <input data-id="{{ $row->id }}" class="dealer-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
      </td>
      <td>
            <button type="button" data-id="{{ $row->id }}" id="editDealerInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editDelarModal">Edit</button>
      </td>
</tr>
@endforeach
<tr>
      <td colspan="5" align="center">{!! $data->links() !!}</td>
</tr>