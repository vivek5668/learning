<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Principal',
            'email' => 'principal@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
