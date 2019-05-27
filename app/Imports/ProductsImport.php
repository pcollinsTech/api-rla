<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'category' => $row[0],
            'sub_category' => $row[1],
            'part_number' => $row[2],
            'description' => $row[3]
        ]);
    }
}
