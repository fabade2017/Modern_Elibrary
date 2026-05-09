<?php

namespace Database\Seeders;
 use App\Models\Permission;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
       

public function run()
{
    /*
    $modules = [
        'Manage Users',
        'Manage Roles',
        'Manage Classes',
        'Manage Arms',
        'Manage Resources',
        'View Dashboard',
    ];

    foreach ($modules as $mod) {
        Permission::firstOrCreate([
            'name' => $mod,
            'slug' => strtolower(str_replace(' ', '_', $mod)),
        ]);
    }
     DB::table('settings')->insert([
            [
                'id'          => 1,
                'key'        => 'site_logo',
                'value' => 'logos/1756314629_logodef.png',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]
            ]);
    DB::table('roles')->insert([
            [
                'id'          => 1,
                'name'        => 'superadmin',
                'description' => 'Superadministrator',
                'active'      => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'          => 2,
                'name'        => 'admin',
                'description' => 'Administrator',
                'active'      => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'id'          => 3,
                'name'        => 'student',
                'description' => 'Student',
                'active'      => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            // Add more roles here if needed...
        ]);
}
*/
        DB::table('badges')->insert( [
            [
                'name' => 'First Download',
                'description' => 'Awarded when you download your first resource.',
                'icon' => 'badges/first-download.png',
                'points_required' => 5,
            ],
            [
                'name' => 'Explorer',
                'description' => 'Unlocked after exploring 5 different categories.',
                'icon' => 'badges/explorer.png',
                'points_required' => 20,
            ],
            [
                'name' => 'Night Owl',
                'description' => 'Earned by accessing resources after 10pm.',
                'icon' => 'badges/night-owl.png',
                'points_required' => 15,
            ],
            [
                'name' => 'Marathon Reader',
                'description' => 'For viewing 10 resources in a week.',
                'icon' => 'badges/marathon.png',
                'points_required' => 30,
            ],
            [
                'name' => 'Top Learner',
                'description' => 'Awarded for being in the weekly leaderboard top 3.',
                'icon' => 'badges/top-learner.png',
                'points_required' => 50,
            ],
        ]);

        // foreach ($badges as $badge) {
        //     Badge::updateOrCreate(['name' => $badge['name']], $badge);
        // }
}
}
