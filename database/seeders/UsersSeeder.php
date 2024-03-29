<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $users = [
            [
                'name' => 'Abraham',
                'email' => 'abrahamaguvasu@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ], [
                'name' => 'Teddy',
                'email' => 'muteshiteddy@gmail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ], [
                'name' => 'Staff 1',
                'email' => 'staff@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        ];

        $admin = Role::create([
            'name' => 'admin',
        ]);
        $admin->givePermissionTo('');

        $staff = Role::create([
            'name' => 'staff',
        ]);

        foreach ($users as $user) {
            $user = User::create($user);
            if($user->name !== 'Staff 1') {
                $user->assignRole($admin);
            } else {
                $user->assignRole($staff);
            }
        }
    }
}
