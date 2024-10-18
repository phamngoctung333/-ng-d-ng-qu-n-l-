<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\phong;
use App\khuktx;
use App\sinhvien;
use App\phieudangky;
use App\canboquanly;
use App\users;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CanboController extends Controller
{
    #----------------Xem danh sách khu----------------------------------------------
    public function admin_khu(){
        $ttkhu = khuktx::ALL();
        return view('pages.admin_khuktx', ['ttkhu' => $ttkhu]);
    }
    #----------------Thêm khu--------------------------------------------------------
    public function store(Request $request) {
        $khu = new Khuktx();
        $khu->tenkhu = $request->tenkhu;
        $khu->gioitinh = $request->gioitinh;
        $khu->diachi = $request->diachi;
        $khu->save();

        // Trả về thông báo thành công
        return redirect()->back()->with(' Thêm khu thành công!');}
    #----------------Xóa khu---------------------------------------------------------
    public function destroy($id) {
        // Tìm khu theo ID
        $khu = Khuktx::find($id);
        // Xóa khu
        $khu->delete();
        // Trả về thông báo thành công
        return redirect()->back()->with('Xóa khu thành công!');
    }
    #----------------Sửa khu----------------------------------------------------------
    public function update(Request $request, $id) {
        $khu = Khuktx::find($id);
        // Cập nhật thông tin khu
        $khu->tenkhu = $request->tenkhu;
        $khu->gioitinh = $request->gioitinh;
        $khu->diachi = $request->diachi;
        $khu->save();
        // Trả về thông báo thành công
        return redirect()->back()->with('Cập nhật khu thành công!');
    }
    #----------------Xem chi tiết phòng khu-------------------------------------------
    public function phongkhu($id){
        $ttphong = phong::where('id_khu', '=', $id)
        ->orderBy('sophong', 'asc') // Thêm dòng này để sắp xếp số phòng từ thấp lên cao
        ->paginate(7);
        return view('pages.admin_khuktx', ['ttphong' => $ttphong]);
    }
    #----------------Xem danh sách sinh viên------------------------------------------------------------------
    public function xemsv()
    {
        // Truy vấn toàn bộ sinh viên, bao gồm thông tin người dùng (users), phiếu đăng ký (phieudangky), và phòng (phong)
        $ttsv = sinhVien::with(['users', 'phieudangky.phong.khuktx'])->get();
        // Trả về view với danh sách sinh viên và thông tin khu mà sinh viên đang ở nếu có
        return view('pages.admin_sv', ['ttsv' => $ttsv]);
    }

    #----------------xem thông tin sinh viên
    public function xem_ttsv($mssv){
        $ttsv = sinhvien::with('users', 'phieudangky.phong.khuktx')->get()->where('mssv', $mssv)->first();
        return view('pages.admin_ttsv', ['ttsv' => $ttsv]);
    }
    
    #----------------Sửa thông tin sinh viên
    public function capnhatSinhVien(Request $request, $mssv)
    {
        // Tìm sinh viên theo mã số sinh viên (mssv)
        $sinhVien = sinhvien::where('mssv', $mssv)->first();

        // Cập nhật thông tin sinh viên
        $sinhVien->nssv = $request->input('nssv', $sinhVien->nssv); // Nếu không có giá trị mới thì giữ nguyên
        $sinhVien->gtsv = $request->input('gtsv', $sinhVien->gtsv);
        $sinhVien->lop = $request->input('lop', $sinhVien->lop);
        $sinhVien->khoa = $request->input('khoa', $sinhVien->khoa);
        $sinhVien->qqsv = $request->input('qqsv', $sinhVien->qqsv);
        $sinhVien->sdt = $request->input('sdt', $sinhVien->sdt);
        $sinhVien->save();

        // Cập nhật thông tin trong bảng users
        $user = users::where('email', $sinhVien->email)->first();
        if ($user) {
            $user->name = $request->input('name', $user->name); // Cập nhật tên nếu có giá trị, nếu không giữ nguyên
            $user->email = $request->input('email', $user->email); // Cập nhật email nếu có giá trị, nếu không giữ nguyên
            $user->save();
        }

        return redirect()->back()->with('success', 'Cập nhật thành công.');
    }
    #----------------Xóa Sinh Viên
    public function deleteStudent($email)
    {
        // Xóa sinh viên từ bảng sinhvien
        sinhVien::where('email', $email)->delete();

        // Xóa tài khoản từ bảng users
        users::where('email', $email)->delete();

        return redirect()->back()->with('success', 'Xóa sinh viên và tài khoản thành công.');
    }


    #----------------Xem Danh sách phòng--------------------------------------------------------------------------------
    public function ql_phong(){
        $id_khu = canboquanly::where('email',Auth::user()->email)->value('id_khu');
        $ttphong = phong::where('id_khu', $id_khu)->orderBy('sophong', 'asc')->paginate(7);
        return view('pages.cbql_phong',['ttphong'=>$ttphong]);
    }
    #----------------Thêm Phòng---------------------------------------------------------------------------------
    public function them_phong(Request $request){
        $id_khu = canboquanly::where('email',Auth::user()->email)->value('id_khu');
        $phong = new phong();
        $phong->sophong = $request->sophong;
        $phong->id_khu = $id_khu;
        $phong->snmax = $request->snmax;
        $phong->gia = $request->gia;
        $phong->sncur = 0; // Hoặc giá trị mặc định mà bạn muốn
        $phong->save();
        return redirect()->back();
    }
    #----------------Sửa phòng-------------------------------------
    public function sua_phong(Request $request, $id) {
        $phong = phong::find($id);
        // Cập nhật thông tin khu
        $phong->snmax = $request->input('snmax');
        $phong->gia = $request->input('gia');
        $phong->save();
        // Trả về thông báo thành công
        return redirect()->back()->with('Cập nhật khu thành công!');
    }
    #----------------Xóa phòng-------------------------------------
    public function cbql_xoa_phong($id)
    {
        // Tìm phòng cần xóa theo id
        $phong = phong::find($id);

        // Kiểm tra xem phòng có tồn tại không
        if (!$phong) {
            return redirect()->back()->withErrors(['message' => 'Phòng không tồn tại!']);
        }

        // Xóa phòng
        $phong->delete();

        // Điều hướng về trang trước đó
        return redirect()->back()->with('success', 'Xóa phòng thành công!');
    }

    #-------------Xem thông tin Sinh viên cán bộ ------------------------------------------------------------------------------
    public function cbql_ttsv(){
        return view('pages.cbql_ttsv');
    }
    public function cbql_cpsv(){
        return view('pages.cbql_cpsv');
    }
    public function cbql_sv()
    {
        $id_khu = canboquanly::where('email', Auth::user()->email)->value('id_khu');

        // Lọc sinh viên đang ở trong khu mà cán bộ quản lý
        $ttsv = sinhvien::whereHas('phieudangky', function($query) use ($id_khu) {
            $query->where('trangthaidk', 'success')
                  ->whereHas('phong', function($query) use ($id_khu) {
                      $query->where('id_khu', $id_khu);
                  });
        })->with('users', 'phieudangky.phong')->get();
    
        // Kiểm tra nếu không có sinh viên nào ở trong khu của cán bộ
        if ($ttsv->isEmpty()) {
            return view('pages.admin_sv', ['ttsv' => [], 'message' => 'Hiện chưa có sinh viên nào ở trong khu này.']);
        }
        // Trả về view với danh sách sinh viên
        return view('pages.admin_sv', ['ttsv' => $ttsv]);
    }

    #---------------Duyệt ĐK--------------------------------------------------------------------------------------------
    public function cbql_duyetdk(){
            // Lấy id_khu của cán bộ quản lý dựa trên email
        $id_khu = canboquanly::where('email', Auth::user()->email)->value('id_khu');
        
        // Lấy thông tin phòng thuộc id_khu
        $ttphong = phong::where('id_khu', $id_khu)->get();
        
        // Lấy danh sách phiếu đăng ký với tình trạng 'registered'
        $list = phieudangky::where('trangthaidk', 'registered')
            ->whereHas('phong', function($query) use ($id_khu) {
                $query->where('id_khu', $id_khu);
            })
            ->orderBy('ngaydk', 'asc') // Sắp xếp theo ngày đăng ký từ lâu đến gần nhất
            ->get();

        // Trả về view cùng với danh sách phiếu đăng ký và thông tin phòng
        return view('pages.cbql_duyetdk', ['list' => $list, 'ttphong' => $ttphong]);
    }

    public function cbql_thongke() {
        $list_nam = phieudangky::select('nam')->groupBy('nam')->get();
        $year = Date('Y');
        $id_khu = canboquanly::where('email', Auth::user()->email)->value('id_khu');
        
        // Lấy thông tin phòng trong khu
        $phong_list = phong::where('id_khu', $id_khu)->get();
    
        // Tính tổng số người đang ở
        $sncur = $phong_list->sum('sncur'); // Tổng số người đang ở
        $snmax = $phong_list->sum('snmax'); // Tổng số chỗ ở
        $empty_space = $snmax - $sncur; // Số chỗ còn trống
    
        // Đếm số sinh viên đã đăng ký trong năm và khu đã chọn
        $total_student = DB::table('phieudangky')
            ->join('phong', 'phieudangky.id_phong', '=', 'phong.id')
            ->join('khuktx', 'phong.id_khu', '=', 'khuktx.id')
            ->join('canboquanly', 'khuktx.id', '=', 'canboquanly.id_khu')
            ->where('canboquanly.email', Auth::user()->email) // Lọc theo cán bộ quản lý
            ->where('phieudangky.nam', $year) // Lọc theo năm
            ->where('phieudangky.trangthaidk', '!=', 'cancelled') // Không bao gồm trạng thái cancelled
            ->where('phieudangky.trangthaidk', '!=', 'registered') // Không bao gồm trạng thái registered
            ->count();
    
        // Tổng lệ phí đã nộp
        $total_money = DB::table('phieudangky')
            ->join('phong', 'phieudangky.id_phong', '=', 'phong.id')
            ->join('khuktx', 'phong.id_khu', '=', 'khuktx.id')
            ->join('canboquanly', 'khuktx.id', '=', 'canboquanly.id_khu')
            ->where('canboquanly.email', Auth::user()->email) // Lọc theo cán bộ quản lý
            ->where('phieudangky.nam', $year) // Lọc theo năm
            ->where('phieudangky.trangthaidk', '!=', 'cancelled') // Không bao gồm trạng thái cancelled
            ->where('phieudangky.trangthaidk', '!=', 'registered') // Không bao gồm trạng thái registered
            ->sum('lephi');
    
        return view('pages.cbql_thongke', [
            'snmax'=> $snmax,
            'sncur' => $sncur, // Tổng số người đang ở
            'empty_space' => $empty_space, // Số chỗ còn trống
            'total_student' => $total_student,
            'total_money' => $total_money,
            'list_nam' => $list_nam,
            'year' => $year
        ]);
    }
    
}
