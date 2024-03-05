@if(isset($product_list))
    @foreach($product_list as $row)
    <tr>
        <td>{{ ++$loop->index }}.</td>
        <td>{{ $row->product_model }}</td>
        <td>{{number_format($row->mrp_price,2)}}</td>
        <td>{{number_format($row->msdp_price,2)}}</td>
        <td>{{number_format($row->msrp_price,2)}}</td>
        <td>{{ $row->product_type }}</td>
        <td>{{ $row->category2 }}</td>
        <td>
            <input data-id="{{ $row->product_master_id }}" class="product-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
        </td>
        <td>
            <button type="button" data-id="{{ $row->product_master_id }}" id="editProductInfo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editProductModal">Edit</button> 
            <button type="button" data-id="{{ $row->product_master_id }}" id="productStock" class="btn btn-success btn-sm" data-toggle="modal" data-target="#productStockModal">Stock Maintaince</button>
        </td>
    </tr>
    @endforeach
	<tr><td colspan="9" align="center">{!! $product_list->links() !!}</td></tr>
@endif

                    