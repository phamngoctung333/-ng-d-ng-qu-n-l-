<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phong extends Model
{
    protected $table = "phong";
    public function khuktx(){
        return $this->belongsTo(khuktx::class, 'id_khu');
    }
}
