@extends('master')
@section('content')
<div class="list_phong">
    <h3>
        <i class="fa fa-arrow-circle-o-right"></i>
        Danh sách phòng
    </h3>
    <form action="{{url('cbql_them_phong')}}" method="post" class="form-inline">
        @csrf
        <label for="">Số phòng:</label>
        <input name="sophong" type="number" min="0" required class="form-control"/>
        <label for="">Số người tối đa:</label>
        <input name="snmax" required type="number" min="0" class="form-control"/>
        <label for="">Giá phòng:</label>
        <input name="gia" required type="number" min="0" class="form-control"/>
        <button class="btn btn-success" type="submit">Thêm phòng +</button>
    </form>
    <hr>
    <table class="table table-bordered table-striped datatable" id="table_export">
        <tr>
            <th>Số phòng</th>
            <th>Số người đk hiện tại</th>
            <th>Số người tối đa</th>
            <th>Giá phòng</th>
            <th>Xem</th>
            <th>Thao tác</th>
        </tr>
		@foreach($ttphong as $p)
		<tr>
			<td>{{$p->sophong}}</td>
			<td>{{$p->sncur}}</td>
			<td>
				<span class="view-snmax-{{$p->id}}">{{$p->snmax}}</span>
				<input type="number" class="edit-snmax-{{$p->id}}" value="{{$p->snmax}}" style="display:none;" readonly>
			</td>
			<td>
				<span class="view-gia-{{$p->id}}">{{$p->gia}}</span>
				<input type="number" class="edit-gia-{{$p->id}}" value="{{$p->gia}}" style="display:none;" readonly>
			</td>
			<td><a href="{{route('cbql_ttphong',$p->id)}}"><button>Xem thông tin</button></a></td>
			<td>
				<div style="display: flex; align-items: center;">
					<button id="edit-button-{{$p->id}}" onclick="enableEditing('{{$p->id}}')">Sửa</button>
					<button id="save-button-{{$p->id}}" style="display:none; margin-left: 5px;" onclick="document.getElementById('form-{{$p->id}}').submit();">Lưu</button>
					<button id="cancel-button-{{$p->id}}" style="display:none; margin-left: 5px;" onclick="cancelEditing('{{$p->id}}')">Hủy</button>
					
					<form id="form-{{$p->id}}" action="{{ route('sua_phong', $p->id) }}" method="POST" style="display:inline;">
						@csrf
						@method('PUT')
						<input type="hidden" name="snmax" class="edit-snmax-{{$p->id}}" value="{{$p->snmax}}">
						<input type="hidden" name="gia" class="edit-gia-{{$p->id}}" value="{{$p->gia}}">
					</form>

					<form action="{{ route('cbql_xoa_phong', $p->id) }}" method="POST" style="display:inline; margin-left: 5px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
						@csrf
						@method('DELETE')
						<button id="delete-button-{{$p->id}}" type="submit">Xóa</button>
					</form>
				</div>
			</td>
		</tr>
		@endforeach
    </table>
</div>
<div class="row">
    <div class="col-xs-6 col-left"></div>
    <div class="col-xs-6 col-right">
        <div class="dataTables_paginate paging_bootstrap">
            {!! $ttphong->links() !!}
        </div>
    </div>
</div>

<script>
	let originalData = {}; // Biến để lưu trữ giá trị gốc

	function enableEditing(rowId) {
		// Lưu giá trị gốc trước khi chỉnh sửa
		originalData[rowId] = {
			snmax: document.querySelector(`.edit-snmax-${rowId}`).value,
			gia: document.querySelector(`.edit-gia-${rowId}`).value
		};

		// Chuyển sang chế độ chỉnh sửa
		document.querySelector(`.view-snmax-${rowId}`).style.display = "none";
		document.querySelector(`.view-gia-${rowId}`).style.display = "none";

		document.querySelector(`.edit-snmax-${rowId}`).style.display = "inline";
		document.querySelector(`.edit-gia-${rowId}`).style.display = "inline";
		document.querySelector(`.edit-snmax-${rowId}`).removeAttribute('readonly');
		document.querySelector(`.edit-gia-${rowId}`).removeAttribute('readonly');

		document.getElementById(`save-button-${rowId}`).style.display = "inline"; // Hiện nút Lưu
		document.getElementById(`cancel-button-${rowId}`).style.display = "inline"; // Hiện nút Hủy
		document.getElementById(`edit-button-${rowId}`).style.display = "none"; // Ẩn nút Sửa
		document.querySelector(`#delete-button-${rowId}`).style.display = "none"; // Ẩn nút Xóa
	}

	function cancelEditing(rowId) {
		// Đưa về giá trị gốc
		document.querySelector(`.edit-snmax-${rowId}`).value = originalData[rowId].snmax;
		document.querySelector(`.edit-gia-${rowId}`).value = originalData[rowId].gia;

		// Chuyển về chế độ xem
		document.querySelector(`.view-snmax-${rowId}`).style.display = "inline";
		document.querySelector(`.view-gia-${rowId}`).style.display = "inline";

		document.querySelector(`.edit-snmax-${rowId}`).style.display = "none";
		document.querySelector(`.edit-gia-${rowId}`).style.display = "none";
		document.querySelector(`.edit-snmax-${rowId}`).setAttribute('readonly', true);
		document.querySelector(`.edit-gia-${rowId}`).setAttribute('readonly', true);

		document.getElementById(`save-button-${rowId}`).style.display = "none"; // Ẩn nút Lưu
		document.getElementById(`cancel-button-${rowId}`).style.display = "none"; // Ẩn nút Hủy
		document.getElementById(`edit-button-${rowId}`).style.display = "inline"; // Hiện nút Sửa
		document.querySelector(`#delete-button-${rowId}`).style.display = "inline"; // Hiện nút Xóa lại
	}

</script>
@endsection
