<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Schema::disableForeignKeyConstraints();
        // Role::truncate();
        // Schema::enableForeignKeyConstraints();
        
        // $data = [
        //     'super_admin','system_admin','ketua','pegawai'
        // ];

        // foreach ($data as $value) {
        //     Role::insert([
        //         'nama' => $value,
        //         'created_at' => now(),
        //         'created_at' => now()
        //     ]);
        // }
    }
}
