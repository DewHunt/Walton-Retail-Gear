@if(isset($productSalesReport) && !$productSalesReport->isEmpty())
    @foreach($productSalesReport as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->product_model }}</td>
        <td>{{ $row->saleQty }}</td>
        <td style="text-align: center;">
        <button type="button" data-id="{{ $row->product_model }}" id="viewProductSalesDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewProductSalesDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
        </td>
    </tr>
    @endforeach
    <tr><td colspan="4" align="center">{!! $productSalesReport->links() !!}</td></tr>
@endif

    


                    