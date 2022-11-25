<?php

namespace Database\Seeders;

use App\Models\ParentLand;
use App\Models\Store;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class ParentLandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParentLand::factory(1)->hasAttached(
            User::factory()->count(6)
                ->create()
                ->each(
                    function ($user) {
                        $user->assignRole('upt-owner');
                    }
                )
        );

        // User::factory()->count(6)
        //     // ->has($fashionStore)
        //     ->create()
        //     ->each(
        //         function ($user) {
        //             $user->assignRole('upt-owner');
        //         }
        //     );

        // $luxuryPhoneStore = ParentLand::factory(1)->hasAttached(
        //     User::factory()->count(1)
        //         ->create()
        //         ->each(
        //             function ($user) {
        //                 $user->assignRole('upt-admin');
        //             }
        //         )
        // );

        // $budgetPhoneStore = ParentLand::factory(1)->hasAttached(
        //     User::factory()->count(1)
        //         ->create()
        //         ->each(
        //             function ($user) {
        //                 $user->assignRole('upt-admin');
        //             }
        //         )
        // );;

        // User::factory()->count(1)
        //     ->has($luxuryPhoneStore)
        //     ->has($budgetPhoneStore)
        //     ->create()
        //     ->each(
        //         function ($user) {
        //             $user->assignRole('upt-owner');
        //         }
        //     );
    }
}
