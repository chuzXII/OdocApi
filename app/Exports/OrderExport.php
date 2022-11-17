<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection,WithTitle,WithHeadings,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('tb_cache_order')->select("id_order","id_pelanggan","jrawat","keluhan","status","create_pending","create_proses","create_selesai" )->get();
    }
    public function title(): string
    {
        return 'Order';
    }
    public function headings(): array
    {
        return["id_order","id_pelanggan","jenis_rawat","keluhan","status","create_pending","create_proses","create_selesai"];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true,'size' => 24],],
        ];
    }
}
