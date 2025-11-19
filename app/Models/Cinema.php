<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //mendaftar softdeletes
    use SoftDeletes;
    //mendaftarkan nama-column yang akan diisi, nama nama column selain id dan timestamp
    protected $fillable = ['name', 'location'];


 public function schedules()
    {
        // pendefinisian jenis relasi (one to one/one to many)
        return $this->hasMany(Schedule::class);
    }
}
