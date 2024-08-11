<?php

namespace Database\Seeders;

use App\Imports\ModuleImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new ModuleImport, base_path().'/database/seeders/Modules.xlsx');
    }
}
