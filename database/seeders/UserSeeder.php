<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dropship.com',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');
    }
}
