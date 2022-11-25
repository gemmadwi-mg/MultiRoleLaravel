<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            // ParentLandSeeder::class,
            // BrandSeeder::class,
            // ProductLineSeeder::class,
            // CategorySeeder::class
        ]);
    }
}
