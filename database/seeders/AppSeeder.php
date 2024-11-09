<?php

namespace Database\Seeders;

use App\Models\App;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        App::create([
            'app_code' => 'APP001',
            'app_name' => 'Inventory Management',
            'app_group' => 'Operations',
            'app_url' => 'inventory-management',
            'data_status' => true,
        ]);

        App::create([
            'app_code' => 'APP002',
            'app_name' => 'HR Portal',
            'app_group' => 'HR',
            'app_url' => 'hr-portal',
            'data_status' => true,
        ]);
    }
}
