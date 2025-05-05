<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SpeakerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure user 1 exists for foreign key constraint
        if (!User::find(1)) {
            User::factory()->create([
                'id' => 1,
                'role' => 'admin',
                'first_name' => 'Kyle',
                'last_name' => 'McPherson',
                'email' => 'kylem.mcpherson@outlook.com',
                'password' => bcrypt('Morgan146@'),
                'email_verified_at' => now(),
            ]);
        }

        // Create only speakers
        \App\Models\Speaker::factory(10)->create([
            'user_id' => 1,
            'photo' => 'https://picsum.photos/200/300',
            'created_at' => now(),
        ]);

        $this->command->info('Speakers seeded successfully!');
    }
}
