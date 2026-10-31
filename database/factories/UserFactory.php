<?php

namespace Database\Factories;

use App\Enums\BloodType;
use App\Enums\Rhesus;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => UserRole::PENDONOR->value,
            'phone' => '0812'.fake()->unique()->numerify('########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'blood_type' => fake()->randomElement(BloodType::values()),
            'rhesus' => fake()->randomElement(Rhesus::values()),
            'gender' => fake()->randomElement(['L', 'P']),
            'birth_date' => fake()->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
            'weight_kg' => fake()->numberBetween(50, 85),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
