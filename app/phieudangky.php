<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class phieudangky extends Model
{
    protected $table = "phieudangky";
    public function phong()
    {
        return $this->belongsTo(phong::class, 'id_phong');
    }
}
