@if(isset($soldImeList))
    @foreach($soldImeList as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->barcode }}</td>
        <td>{{ $row->barcode2 }}</td>
        <td>
            @if($row->status == 0)
            <button class="btn btn-warning btn-xs" style="padding: 2px 6px;">Sold</button>
            @else
            <button class="btn btn-success btn-xs" style="padding: 2px 6px;">Available</button>
            @endif
        </td>
        <td style="text-align: center;">
            <button type="button" data-id="{{ $row->product_master_id }}" id="viewProductInfo" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewProductModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
    </tr>
    @endforeach
    <tr><td colspan="5" align="center">{!! $soldImeList->links() !!}</td></tr>
@endif


                    