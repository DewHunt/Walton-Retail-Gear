@if(isset($offerList))
    @foreach($offerList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>
            @if(isset($row->offer_pic) && !empty($row->offer_pic) && $row->offer_pic !=null)
            <img src="{{ $row->offer_pic }}" alt="" width="70" height="70"/></td>
            @else
            <img src="{{ asset('public/upload/no-image.png') }}" alt="" width="70" height="70"/></td>
            @endif
        </td>
        <td>{{ $row->title }}</td>
        <td>{{ $row->sdate }}</td>
        <td>{{ $row->edate }}</td>
        <td>
            @if(isset($row->zone) && !empty($row->zone) && $row->zone !=null)
            @foreach(json_decode($row->zone, true) as $key => $value)
            {{ $value }}, 
            @endforeach
            @endif
        </td>
        <td>
            <input data-id="{{ $row->id }}" class="offer-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <form action="{{ route('promoOffer.destroy',$row->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" data-id="{{ $row->id }}" id="editOfferInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editOfferModal">Edit</button>

            <button onclick="return confirm('Are you sure to delete?')" type="submit" class="btn btn-danger btn-sm">
                Delete
            </button>
            </form>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="8" align="center">{!! $offerList->links() !!}</td></tr>
@endif
    


                    