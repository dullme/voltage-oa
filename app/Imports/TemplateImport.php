<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Excel;

class TemplateImport implements ToModel
{

    public function model(array $row)
    {
        return $row;
    }

}
