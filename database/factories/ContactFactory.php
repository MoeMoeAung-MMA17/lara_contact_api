<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        return [
            "name" => fake()->name(),
            "country_code" => "95",
            "phone_number" => fake()->phoneNumber(),
            "email" => fake()->email(),
            "user_id" => rand(1,10)
            
        ];
    }
}
