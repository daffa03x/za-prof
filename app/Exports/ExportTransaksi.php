<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportTransaksi implements FromCollection, WithHeadings
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
            'Id Event',
            'Event',
            'Invoice',
            'Name',
            'Email',
            'Telepon',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Status Pembayaran',
            'Tanggal Register',
            'Tanggal Pembayaran',
            'Payment',
            'Tanggal di buat'
        ];
    }
}
