<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sinhvien extends Model
{
    protected $table = "sinhvien";

    public function users()
    {
        return $this->belongsTo(users::class, 'email', 'email'); // Đảm bảo 'email' là cột chính xác
    }
    public function phieudangky()
    {
        return $this->hasOne(PhieuDangKy::class, 'mssv', 'mssv'); // Liên kết với bảng phieudangky
    }
    protected $primaryKey = 'mssv'; // Đặt cột mssv là khóa chính
    public $incrementing = false; // Nếu mssv không phải là số nguyên
    protected $keyType = 'string'; // Nếu mssv là chuỗi
}
