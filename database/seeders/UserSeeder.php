<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;
 
use App\Models\User;
use Illuminate\Database\Seeder;
 
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@mplc',
            'password' => bcrypt('12345'),
        ]);
 
        $user->roles()->attach(1);
    }
}