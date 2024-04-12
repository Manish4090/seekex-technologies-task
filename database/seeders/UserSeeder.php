<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            'name'  => 'User',
            'email' => 'nicky@mailinator.com',
            'password' => bcrypt('123456789')
        ];
        User::create($user);
    }
}
