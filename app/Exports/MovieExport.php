<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
// class untuk membuat th oada table excel
use Maatwebsite\Excel\Concerns\WithHeadings;
// class untuk membuat td pada table excel
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;


class MovieExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Movie::all();
    }

    // menentilam oso th
    public function headings(): array
    {
        return ['No', 'Judul', 'Durasi', 'Genre', 'Sutradara', 'Usia Minimal', 'Poster', 'Sinopsis', 'Status' ];
    }

    // mengisi td
    public function map($movie): array
    {
        return [
            ++$this->key,
            $movie->title,
            // 02:00 jadi 2 jam 00 menit
            // format("H") ambil jam dari duration, format("i") ambil menit dari duration
            Carbon::parse($movie->duration)->format("H") . " jam " . Carbon::parse
            ($movie->duration)->format("i") . " Menit",
            $movie->genre,
            $movie->director,
            $movie->age_rating . "+",
            // poster berupa url public : asset()
            asset('storage') . '/' . $movie->poster,
            $movie->description,
            // jika actived 1 maka aktif, jika 0 maka tidak aktif
            $movie->actived == 1 ? 'Aktif' : 'Tidak Aktif'


        ];
    }
}
