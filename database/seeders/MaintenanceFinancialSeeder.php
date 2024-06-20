<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MaintenanceFinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'items.view']);
        Permission::create(['name' => 'items.create']);
        Permission::create(['name' => 'items.edit']);
        Permission::create(['name' => 'items.delete']);
    }
}
