Dear User
<h3>You'r Stock List Goes To Here</h3>
<table width="100%" border="0" cellspacing="0" cellpadding="6" align="left"  style="font-size: 12px; font-family: 'Helvetica Neue', Helvetica, Arial, Tahoma, sans-serif; color:#ffffff" class="table40">
    <tr style="background:#919292">
        <th>Dealer Name</th>
        <th>Dealer Code</th>
        <th>Dealer Phone</th>
        <th>Retailer Name</th>
        <th>Retailer Phone</th>
        <th>Model</th>
        <th>Available Qty</th>
    </tr>
    <tbody>
    @php $i=0; @endphp
    @foreach($rowDataList as $key=>$rowData)                                            
        <tr style="color:#000000;background:#{{ ($i % 2 == 0) ?'eeeeee':'ffffff'}}">
            <td align="center">{{ ($rowData['DealerName']) ?   $rowData['DealerName'] : '--' }}</td>
            <td align="center">{{ ($rowData['DealerCode']) ?   $rowData['DealerCode'] : '--' }}</td>
            <td align="center">{{ ($rowData['DealerPhone']) ?  $rowData['DealerPhone'] : '--' }}</td>
            <td align="center">{{ ($rowData['RetailerName']) ? $rowData['RetailerName'] : '--' }}</td>
            <td align="center">{{ ($rowData['RetailerPhone']) ? $rowData['RetailerPhone'] : '--' }}</td>
            <td align="center">{{ ($rowData['Model']) ? $rowData['Model'] : '--' }}</td>
            <td align="center"><span style="background:{{ ($rowData['Status']) ? $rowData['Status'] : 'red'  }};color:#{{ ($rowData['Status'] == 'yellow') ? '000' : 'fff'  }};padding:5px;">{{ ($rowData['AvailableQty']) ? $rowData['AvailableQty'] : '--'  }}</span></td>
        </tr>
    @php $i++; @endphp
    @endforeach
    </tbody>                     
</table>

<p>Please Confirm To Dealer Or Retailer Update To Stock</p>

Best Regards
Admin
