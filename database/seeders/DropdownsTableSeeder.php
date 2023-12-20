<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('dropdowns')->insert([
            [
                'id' => 1,
                'category' => 'Role',
                'name_value' => 'Super Admin',
                'code_format' => 'SPA',
                'created_at' => '2023-12-17 11:46:14',
                'updated_at' => '2023-12-17 11:46:14',
            ],
            // Add more dropdowns if needed
        ]);
    }
}
