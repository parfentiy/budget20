<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::truncate();
        \App\Models\Account::truncate();
        \App\Models\CashFlow::truncate();

        \App\Models\User::factory()->create([
             'name' => 'Test User',
             'email' => 'test@example.com',
             'password' => '1111',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Семья Парфентий',
            'email' => 'parfentiyp@gmail.com',
            'password' => 'Iphone5s',
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Банки',
            'category' => 0,
            'order_number' => 1,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Статьи расхода',
            'category' => 0,
            'order_number' => 2,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Доходы',
            'category' => 0,
            'order_number' => 3,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Карта 4686',
            'category' => 1,
            'order_number' => 1,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => '10%',
            'category' => 1,
            'order_number' => 2,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => '5%',
            'category' => 1,
            'order_number' => 3,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Продукты',
            'category' => 2,
            'order_number' => 1,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Еда ГБЛ',
            'category' => 2,
            'order_number' => 2,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Бухалово Марина',
            'category' => 2,
            'order_number' => 3,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'ЗП Петя',
            'category' => 3,
            'order_number' => 1,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'Аванс Марина',
            'category' => 3,
            'order_number' => 2,
            'user_id' => 2,
        ]);

        \App\Models\Account::factory()->create([
            'name' => 'ЗП Марина',
            'category' => 3,
            'order_number' => 3,
            'user_id' => 2,
        ]);

        \App\Models\CashFlow::factory(50)->create();
        \App\Models\PlanBudget::factory(10)->create();
    }
}
