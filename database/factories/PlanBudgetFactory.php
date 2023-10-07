<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanBudget>
 */
class PlanBudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = Account::where('user_id', 2)->where('category', '!=', '0')->pluck('id');

        for ($i = 1; $i <= 25; $i++) {
            $dataset[] = [
                'account' => fake()->randomElement($categories),
                'sum' => fake()->numberBetween(100, 5000),
                'order' => $i,
            ];
        }

        for ($i = 1; $i <= 10; $i++) {
            $incomes[] = [
                'account' => fake()->randomElement($categories),
                'sum' => fake()->numberBetween(3000, 10000),
                'order' => $i,
            ];
        }

        //Log::info(json_encode($dataset));
        return [
            //
            'month' => fake()->numberBetween(1,12),
            'year' => fake()->numberBetween(2023, 2024),
            'user_id' => 2,
            'description' => fake()->sentence,
            'dataset' => json_encode($dataset),
            'incomes' => json_encode($incomes),
        ];
    }
}
