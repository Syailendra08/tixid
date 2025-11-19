<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['cinema_id', 'movie_id', 'hours',
    'price'];

     // memanggil relasi cinema. schedule mempunyai FK cinema
    // karna one (cinema) to many (schedule) : nama tunggal
    public function cinema()
    {
        // untuk table yg memegang FK gunakan ini
        return $this->belongsTo(Cinema::class);
    }

    // same thing with cinema
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // casts : memastikan tipe data. agar json menjadi array
    protected function casts(): array
    {
        return [
            'hours' => 'array'
        ];
    }
}


