<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'PU SDA Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => 'admin@1234',
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole('super-admin');

        $upt_kediri = User::create([
            'name' => 'UPT PSDA Kediri',
            'email' => 'userkediri@gmail.com',
            'email_verified_at' => now(),
            'password' => 'userkediri@1234',
            'remember_token' => Str::random(10),
        ]);
        $upt_kediri->assignRole('upt-owner');

        $upt_kediri = User::create([
            'name' => 'UPT PSDA Lumajang',
            'email' => 'userlumajang@gmail.com',
            'email_verified_at' => now(),
            'password' => 'userlumajang@1234',
            'remember_token' => Str::random(10),
        ]);
        $upt_kediri->assignRole('upt-owner');

        // User::factory()->count(6)
        //     ->create()
        //     ->each(
        //         function ($user) {
        //             $user->assignRole('upt_owner');
        //         }
        //     );
    }
}
