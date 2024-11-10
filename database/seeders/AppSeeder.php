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
            'app_name' => 'Inventory',
            'app_group' => 'Operations',
            'app_url' => 'inventory',
            'data_status' => true,
        ]);

        App::create([
            'app_code' => 'APP002',
            'app_name' => 'HR Portal',
            'app_group' => 'HR',
            'app_url' => 'hr-portal',
            'data_status' => true,
        ]);
        App::create([
            'app_code' => 'APP003',
            'app_name' => 'Absensi Kehadiran',
            'app_group' => 'HR',
            'app_url' => 'absensi-kehadiran',
            'data_status' => true,
        ]);

        App::create([
            'app_code' => 'APP004',
            'app_name' => 'Log Monitor',
            'app_group' => 'IT',
            'app_url' => 'log-monitor',
            'data_status' => true,
        ]);
        App::create([
            'app_code' => 'APP005',
            'app_name' => 'Picklist',
            'app_group' => 'Operations',
            'app_url' => 'picklist',
            'data_status' => true,
        ]);
        App::create([
            'app_code' => 'APP006',
            'app_name' => 'Packlist',
            'app_group' => 'Operations',
            'app_url' => 'packlist',
            'data_status' => true,
        ]);
        App::create([
            'app_code' => 'APP007',
            'app_name' => 'Pajak',
            'app_group' => 'Accounting',
            'app_url' => 'tax',
            'data_status' => true,
        ]);
        App::create([
            'app_code' => 'APP008',
            'app_name' => 'Penjualan',
            'app_group' => 'Sales',
            'app_url' => 'sale',
            'data_status' => true,
        ]);
    }
}
