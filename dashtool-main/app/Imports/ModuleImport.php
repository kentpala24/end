<?php

namespace App\Imports;

use App\Models\Module;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModuleImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return Module::create([
            'id' => $row['id'],
            'status' => $row['status'],
            'type' => $row['type'],
            'nom' => $row['nom'],
            'desc' => $row['desc'],
            'icon' => $row['icon'],
            'url_module' => $row['url_module'],
            'color' => $row['color'],
            'url_sub_module' => $row['url_sub_module'],
            'show_on' => $row['show_on'],
            'back_module_id' => $row['back_module_id'],
            'module_id' => $row['module_id'],
        ]);
    }
}
