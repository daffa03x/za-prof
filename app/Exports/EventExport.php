<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $event;
    public function __construct(Collection $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        return $this->event;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama Event',
            'Mitra',
            'Website',
            'Status',
            'Waktu Mulai',
            'Waktu Berakhir',
            'Nama Tempat',
            'Alamat',
            'Kota',
            'Jumlah Tiket',
            'Harga',
            'Tanggal di buat'
        ];
    }
}
