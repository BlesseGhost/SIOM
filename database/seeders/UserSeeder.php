<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ADMIN
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'roles' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // PEMBINA ORMAWA
        DB::table('users')->updateOrInsert(
            ['email' => 'pembina@mail.com'],
            [
                'name' => 'Pembina Ormawa',
                'password' => Hash::make('pembina123'),
                'roles' => 'pembina',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // KEMAHASISWAAN
        DB::table('users')->updateOrInsert(
            ['email' => 'kemahasiswaan@mail.com'],
            [
                'name' => 'Kemahasiswaan',
                'password' => Hash::make('kemahasiswaan123'),
                'roles' => 'kemahasiswaan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // WAKIL REKTOR 3 (WR3)
        DB::table('users')->updateOrInsert(
            ['email' => 'wr3@mail.com'],
            [
                'name' => 'Wakil Rektor 3',
                'password' => Hash::make('wr3123'),
                'roles' => 'wr3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
