<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfileExport implements FromCollection,WithTitle,WithHeadings,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('tb_profile')->select("id_profile","user_id","nama","tgllahir","umur","jkelamin","nohp","alamat","created_at","updated_at")->get();
    }
    public function title(): string
    {
        return 'Profile';
    }
    public function headings(): array
    {
        return["id_profile","user_id","nama","tgllahir","umur","jkelamin","nohp","alamat","created_at","updated_at"];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true,'size' => 24],],
        ];
    }
}
