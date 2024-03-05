@if(isset($salesIncentiveReportList))
    @foreach($salesIncentiveReportList as $row)
        @if($row->total_qty > 0 && $row->total_incentive > 0)
            <tr>
                <td>{{ ++$loop->index }}.</td>
                <td>{{ ucfirst($row->category) }}</td>
                <td>{{ $row->bp_name }}</td>
                <td>{{ $row->retailer_name }}</td>
                <td>{{ $row->total_qty }}</td>
                <td>{{ number_format($row->total_incentive,2) }}</td>
                <td style="text-align: center;">
                    @if($row->bp_id > 0)
                    <a href="javascript:void(0)">
                    <button type="button" data-id="{{ $row->bp_id }}" id="bpSaleIncentiveDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewSaleIncentiveDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </a>
                    @elseif($row->retailer_id > 0)
                    <a href="javascript:void(0)">
                    <button type="button" data-id="{{ $row->retailer_id }}" id="retailSaleIncentiveDetails" class="btn cur-p btn-info btn-xs" data-toggle="modal" data-target="#viewSaleIncentiveDetailsModal" style="padding: 2px 6px;"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </a>
                    @endif
                </td>
            </tr>
        @else
        <tr><td colspan="8" style="text-align: center;color:red">Data Not Found</td></tr>
        @endif
    @endforeach
    <tr><td colspan="8" align="center">{!! $salesIncentiveReportList->links() !!}</td></tr>
@endif
    


                    