<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
        ]);
        $cs = User::create([
            'name' => 'Customer Service 1',
            'username' => 'cs1',
            'email' => 'cs1@admin.com',
            'password' => Hash::make('admin123'),
        ]);
        $wd = User::create([
            'name' => 'Tim Withdraw',
            'username' => 'wd1',
            'email' => 'wd1@admin.com',
            'password' => Hash::make('admin123'),
        ]);

        $admin->roles()->attach(1);
        $cs->roles()->attach(3);
        $wd->roles()->attach(4);
    }
}
