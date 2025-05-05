<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => 1,
            'partner_type' => $this->faker->randomElement(['individual', 'company']),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company_name' => $this->faker->company(),
            'company_website' => $this->faker->url(),
            'type_of_support' => $this->faker->randomElement(['financial', 'in-kind', 'media']),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'logo' => $this->faker->imageUrl(),
            'created_at' => now(),
        ];
    }
}
