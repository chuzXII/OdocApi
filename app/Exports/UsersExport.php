<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection,WithHeadings,WithStyles,WithTitle
{
    public function collection()
    {
        return DB::table('users')->select('id','username','role','created_at','updated_at')->get();
    }
    public function title(): string
    {
        return 'User';
    }
    public function headings(): array
    {
        return ['id','username','role','created_at','updated_at'];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true,'size' => 24],],
        ];
    }
    
}
