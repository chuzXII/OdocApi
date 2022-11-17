<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new UsersExport();
        $sheets[] = new ProfileExport();
        $sheets[] = new OrderExport();

        return $sheets;
    }


}
