<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\PlanBudget;
use Illuminate\Support\Facades\Auth;

class AccountObserver
{
    /**
     * Handle the Account "created" event.
     */
    public function created(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "updated" event.
     */
    public function updated(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "deleted" event.
     */
    public function deleted(Account $account): void
    {
        //
        $isDatasetDeleted = false;
        $isIncomesDeleted = false;
        foreach (PlanBudget::where('user_id', Auth::user()->id)->get() as $planBudget) {
            $currentBudgetItems = json_decode(\App\Models\PlanBudget::whereId($planBudget->id)->pluck('dataset')->first(), true);
            $currentBudgetIncomes = json_decode(\App\Models\PlanBudget::whereId($planBudget->id)->pluck('incomes')->first(), true);
            foreach ($currentBudgetItems as $key => $currentBudgetItem) {
                if($currentBudgetItem['account'] === $account->id) {
                    unset($currentBudgetItems[$key]);
                    $isDatasetDeleted = true;
                }
            }
            foreach ($currentBudgetIncomes as $key => $currentBudgetIncome) {
                if($currentBudgetIncome['account'] === $account->id) {
                    unset($currentBudgetIncomes[$key]);
                    $isIncomesDeleted = true;
                }
            }

            if ($isDatasetDeleted) {
                $planBudget->dataset = $currentBudgetItems;
                $planBudget->save();
            }

            if ($isIncomesDeleted) {
                $planBudget->incomes = $currentBudgetIncomes;
                $planBudget->save();
            }
        }

        return;
    }

    /**
     * Handle the Account "restored" event.
     */
    public function restored(Account $account): void
    {
        //
    }

    /**
     * Handle the Account "force deleted" event.
     */
    public function forceDeleted(Account $account): void
    {
        //
    }
}
