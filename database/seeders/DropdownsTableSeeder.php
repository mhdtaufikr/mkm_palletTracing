<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownsTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data from the table
        DB::table('dropdowns')->truncate();

        // Insert new data
        DB::table('dropdowns')->insert([
            [
                'category' => 'Role',
                'name_value' => 'Super Admin',
                'code_format' => 'SPA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'User',
                'code_format' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'IT',
                'code_format' => 'IT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Destination',
                'name_value' => 'MKM',
                'code_format' => 'MKM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Destination',
                'name_value' => 'KRM',
                'code_format' => 'KRM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Destination',
                'name_value' => 'TJU',
                'code_format' => 'TJU',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Destination',
                'name_value' => 'KTBSP',
                'code_format' => 'KTBSP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Type Pallet',
                'name_value' => 'Engine',
                'code_format' => 'EG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Type Pallet',
                'name_value' => 'TM-Assy',
                'code_format' => 'TS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Type Pallet',
                'name_value' => 'FA',
                'code_format' => 'FA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more dropdowns if needed
        ]);
    }
}
