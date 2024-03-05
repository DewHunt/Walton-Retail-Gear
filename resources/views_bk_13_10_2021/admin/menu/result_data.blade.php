@if(isset($menuList))
	@foreach ($menuList as $row)
	<tr class="row_{{ $row->id }}">
		<td>{{ ++$loop->index }}.</td>
		<td>{{ $row->menu_name }}</td>
		<td>{{ $row->parentName }}</td>
		<td>{{ $row->menu_link }}</td>
		<td>
			<input data-id="{{ $row->id }}" class="menu-toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" @if($row->status == 1 )checked @else {{ ' ' }}@endif>
		</td>
		<td>
			<button type="button" data-id="{{ $row->id }}" id="editmenu" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editmenuModal">Edit</button>
		</td>
	</tr>
	@endforeach
	<tr><td colspan="6" align="center">{!! $menuList->links() !!}</td></tr>
@endif
    


                    