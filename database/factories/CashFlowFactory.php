<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashFlow>
 */
class CashFlowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accounts = \App\Models\Account::where('category', "!=", '0')->pluck('id');
        $users = \App\Models\User::pluck('id');
        return [
            //
            'amount' => fake()->numberBetween(100, 5000),
            'source_account_id' => fake()->randomElement($accounts),
            'dest_account_id' => fake()->randomElement($accounts),
            'operation_date' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'user_id' => fake()->randomElement($users),
            'description' => fake()->sentence(),
        ];
    }
}
