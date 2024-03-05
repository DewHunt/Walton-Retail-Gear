@if(isset($AwardList))
    @foreach($AwardList as $key=>$row)
    @php $num_of_items = count($productNameList); $num_count = 0; @endphp
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->award_title }}</td>
        <td>{{ $row->award_type }}</td>
        <td>{{ $row->min_qty }}</td>
        <td>{{ $row->start_date }}</td>
        <td>{{ $row->end_date }}</td>
        <td>
            <input data-id="{{ $row->id }}" class="award-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <form action="{{ url('award.destroy',$row->id) }}" method="get">
            @csrf
            <a href="{{url('award.edit', \Crypt::encrypt($row->id))}}">
                <button type="button"  class="btn btn-primary btn-sm" >Edit</button>
            </a>
            <button onclick="return confirm('Are you sure to delete?')" type="submit" class="btn btn-danger btn-sm">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="9" align="center">{!! $AwardList->links() !!}</td></tr>
@endif
    


                    