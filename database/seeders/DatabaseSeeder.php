<?php

namespace Database\Seeders;

use \App\Models\Speaker;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'role' => 'admin',
            'first_name' => 'Kyle',
            'last_name' => 'McPherson',
            'email' => 'kylem.mcpherson@outlook.com',
            'password' => bcrypt('Morgan146@'),
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
        ]);

        //create 10 speakers
        Speaker::factory(10)->create([
            //user_ID must be 1
            //this is the admin user
            'user_id' => 1,
            'photo' => 'https://picsum.photos/200/300',
            'created_at' => now(),
        ]);
        // Create only speakers
        Partner::factory(10)->create([
            'user_id' => 1,
            'logo' => 'https://picsum.photos/200/300',
            'created_at' => now(),
        ]);
    }
}
