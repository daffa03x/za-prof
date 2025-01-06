<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CampaignExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $campaign;
    public function __construct(Collection $campaign)
    {
        $this->campaign = $campaign;
    }

    public function collection()
    {
        return $this->campaign;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Nama Campaign',
            'Mitra',
            'Website',
            'Tanggal di buat'
        ];
    }
}
