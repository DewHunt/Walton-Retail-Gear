@if(isset($productSalesReport) && !$productSalesReport->isEmpty())
    @foreach($productSalesReport as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->product_model }}</td>
        <td>{{ $row->saleQty }}</td>
        <td style="text-align: center;">
        <button type="button" data-id="{{ $row->product_model }}{{'~'}}{{ ($row->bp_id > 0) ? 'bp_id':'retailer_id' }}{{'~'}}{{ ($row->bp_id > 0) ? $row->bp_id:$row->retailer_id }}" id="SellerProductSalesDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewProductSalesDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
        <input type="hidden" id="seller" value={{ ($row->bp_id > 0) ? 'bp':'retailer' }}>
        <input type="hidden" id="sellerId" value={{ ($row->bp_id > 0) ? $row->bp_id:$row->retailer_id }}>
    </tr>
    @endforeach
    <tr><td colspan="4" align="center">{!! $productSalesReport->links() !!}</td></tr>
@endif

    


                    