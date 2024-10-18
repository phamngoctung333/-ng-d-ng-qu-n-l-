@extends('master')
@section('title', 'Thông tin sinh viên')
@section('content')
<h3>
    <i class="fa fa-arrow-circle-o-right"></i>
    Thông tin sinh viên
</h3>

<div class="row">
    <div class="col-xs-6 col-left"></div>
    <div class="col-xs-6 col-right">
        <div class="dataTables_filter" id="table_export_filter">
        <form action="{{ url('info_sv')}}" method="post">
            @csrf
            <label>Nhập mã số sinh viên: <input type="text" name="mssv" required></label>
            <button type="submit">Tìm kiếm</button>
            @if(Session::has('flag'))
                <div class="error"><p>{{ Session::get('message') }}</p></div>
            @endif
        </form>
        </div>
    </div>

    <div class="col-md-12">
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#list" data-toggle="tab"><i class="entypo-user"></i>
                    Thông tin sinh viên
                </a>
            </li>
        </ul>
    </div>

    @if(Session::has('flag2'))
        <div class="error"><p>{{ Session::get('message') }}</p></div>
    @endif

    @if(isset($ttsv))
        <div class="tab-content">
            <div class="tab-pane box active" id="list" style="padding: 5px">
                <div class="box-content">
                    <form action="{{ route('capnhatsv', $ttsv->mssv) }}" method="post" class="form-horizontal form-groups-bordered validate" target="_top" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Khu đang ở</label>
                            <div class="col-sm-4">
                                <label class="control-label">
                                    @if($ttsv->phieudangky && $ttsv->phieudangky->trangthaidk == 'success')
                                        {{ $ttsv->phieudangky->phong->khuktx->tenkhu }}
                                    @else
                                        <!-- Ô trống nếu không có trạng thái success -->
                                    @endif
                                </label>
                            </div>
                            <label class="col-sm-2 control-label">Phòng đang ở</label>
                            <div class="col-sm-3">
                                <label class="control-label">
                                    @if($ttsv->phieudangky && $ttsv->phieudangky->trangthaidk == 'success')
                                        {{ $ttsv->phieudangky->phong->sophong }}
                                    @else
                                        <!-- Ô trống nếu không có trạng thái success -->
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mã số sinh viên</label>
                            <div class="col-sm-5">
                                <label class="control-label">{{ $ttsv->mssv }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tên sinh viên</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="name" value="{{ $ttsv->users->name }}" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">E-mail</label>
                            <div class="col-sm-5">
                                <input type="email" class="form-control" name="email" value="{{ $ttsv->email }}" required/>                       
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Số điện thoại</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="sdt" value="{{ $ttsv->sdt }}" required/>
                            </div>
                            <label class="col-sm-2 control-label">Ngày sinh</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="nssv" value="{{ $ttsv->nssv }}" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Giới tính</label>
                            <div class="col-sm-3">
                                <select name="gioitinh" class="form-control required">
                                    <option value="">Chọn</option>
                                    <option value="nam" {{ $ttsv->gtsv == 'nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="nu" {{ $ttsv->gtsv == 'nu' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">Lớp</label>
                            <div class="col-sm-3">
                                 <input type="text" class="form-control" name="lop" value="{{ $ttsv->lop }}" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Khoa</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="khoa" value="{{ $ttsv->khoa }}" required/>
                            </div>
                            <label class="col-sm-2 control-label">Quê quán</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="qqsv" value="{{ $ttsv->qqsv }}" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" class="btn btn-info">Cập nhật thông tin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
