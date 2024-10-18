@extends('master')
@section('content')
<h2>
    <i class="fa fa-arrow-circle-o-right"></i>
    Danh sách sinh viên
</h2>
<table class="table table-bordered table-striped datatable" id="table_export">
    <tr>
        <th>Mã sinh viên</th>
        <th>Tên sinh viên</th>
        <th>Lớp</th>
        <th>Khu đang ở</th>
        <th>Xem thông tin</th>
        <th>Thao tác</th>
    </tr>
    @foreach($ttsv as $sv)
        <tr>
            <td>{{ $sv->mssv }}</td>
            <td>{{ $sv->users->name }}</td>
            <td>{{ $sv->lop }}</td>
            <td>
                @if($sv->phieudangky && $sv->phieudangky->trangthaidk == 'success')
                    {{ $sv->phieudangky->phong->khuktx->ten_khu  }}
                @else
                    <!-- Ô trống nếu không có trạng thái success -->
                @endif
            </td>
            <td><a href="{{route('xem_ttsv', $sv->mssv)}}" class="btn btn-info">Xem</a></td>
            <td>
                <form action="{{ route('delete_student', ['email' => $sv->email]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
@endsection
