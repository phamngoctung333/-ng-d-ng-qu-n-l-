@extends('master')
@section('content')
    <div class="container-content">
        @if(isset($ttphong))
            <div class="list_phong">
                <h3 style="">
                    <i class="fa fa-arrow-circle-o-right"></i>
                    Danh sách phòng
                </h3>
                @if(Session::has('flag'))
                    <div class="error"><p>{{Session::get('message')}}</p></div>
                @endif
                <table class="table table-bordered table-striped datatable" id="table_export">
                    <tr>
                        <th>Số phòng</th>
                        <th>Số người đk hiện tại</th>
                        <th>Số người tối đa</th>
                        <th>Giá phòng</th>
                        <th></th>
                    </tr>
                    @foreach($ttphong as $p)
                        <tr>
                            <td>{{$p->sophong}}</td>
                            <td>{{$p->sncur}}</td>
                            <td>{{$p->snmax}}</td>
                            <td>{{$p->gia}}</td>
                            <td><a href="{{route('cbql_ttphong',$p->id)}}"><button>Xem thông tin</button></a></td>
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
        @else
            <h2 style="">
                <i class="fa fa-arrow-circle-o-right"></i>
                Danh sách khu ở
            </h2>
            <h3><i class="fa fa-arrow-circle-o-right"></i> Thêm Khu Mới</h3>
            <form action="{{ route('store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="tenkhu">Tên Khu:</label>
                    <input type="text" class="form-control" id="tenkhu" name="tenkhu" required>
                </div>
                <div class="form-group">
                    <label for="gioitinh">Giới Tính:</label>
                    <select class="form-control" id="gioitinh" name="gioitinh">
                        <option value="nam">Nam</option>
                        <option value="nu">Nữ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="diachi">Địa Chỉ:</label>
                    <input type="text" class="form-control" id="diachi" name="diachi" required>
                </div>
                <button type="submit" class="btn btn-primary">Thêm Khu</button>
            </form>
            <table class="table table-bordered table-striped datatable" id="table_export">
                <tr>
                    <th>STT</th>
                    <th>Tên Khu ở</th>
                    <th>Giới tính</th>
                    <th>Địa chỉ</th>
                    <th>Danh sách phòng</th>
                    <th>Thao tác</th>
                </tr>
                @foreach($ttkhu as $k)
                    <tr>
                        <td>{{$k->id}}</td>
                        <td>
                            <input type="text" class="edit-field-{{$k->id}}" value="{{$k->tenkhu}}" readonly>
                        </td>
                        <td>
                            <select class="edit-field-gioitinh-{{$k->id}}" disabled>
                                <option value="nam" {{ $k->gioitinh == 'nam' ? 'selected' : '' }}>Nam</option>
                                <option value="nu" {{ $k->gioitinh == 'nu' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="edit-field-{{$k->id}}" value="{{$k->diachi}}" readonly>
                        </td>
                        <td><a href="{{route('student_chonphong',$k->id)}}"><button>Xem</button></a></td>
                        <td>
                            <button id="edit-button-{{$k->id}}" onclick="enableEditing('{{$k->id}}')">Sửa</button>
                            <button id="save-button-{{$k->id}}" style="display:none;" onclick="saveChanges('{{$k->id}}')">Lưu</button>
                            <form action="{{ route('destroy', $k->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa khu này không?');">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
    </div>
    </div>
    <script>
        function enableEditing(rowId) {
            document.querySelectorAll(`.edit-field-${rowId}`).forEach(element => {
                element.removeAttribute('readonly');
                element.style.border = "1px solid #ccc";
            });
            document.querySelector(`.edit-field-gioitinh-${rowId}`).disabled = false; // Bật chọn giới tính
            document.getElementById(`save-button-${rowId}`).style.display = "inline";
            document.getElementById(`edit-button-${rowId}`).style.display = "none";
        }

        function saveChanges(rowId) {
            // Lấy dữ liệu đã chỉnh sửa
            const tenKhu = document.querySelector(`.edit-field-${rowId}`).value;
            const gioiTinh = document.querySelector(`.edit-field-gioitinh-${rowId}`).value; // Lấy giới tính
            const diaChi = document.querySelectorAll(`.edit-field-${rowId}`)[1].value; // Lấy địa chỉ

            // Gửi request cập nhật thông tin về server
            fetch(`update/${rowId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tenkhu: tenKhu, gioitinh: gioiTinh, diachi: diaChi })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cập nhật thành công!');
                    // Chuyển ô nhập về trạng thái readonly sau khi lưu
                    enableEditing(rowId); // Để tắt chỉnh sửa sau khi lưu
                    // Tắt trường nhập
                    document.querySelectorAll(`.edit-field-${rowId}`).forEach(element => {
                        element.setAttribute('readonly', true);
                        element.style.border = "none"; // Bỏ viền
                    });
                    document.querySelector(`.edit-field-gioitinh-${rowId}`).disabled = true; // Tắt chọn giới tính
                } else {
                    alert('Cập nhật thất bại!');
                }
            });
        }
    </script>

    @endif
@endsection
