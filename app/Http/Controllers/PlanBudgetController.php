<?php

namespace App\Http\Controllers;

use App\Models\PlanBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanBudgetController extends Controller
{
    //
    public function index() {
        if (isset(request()->delete)) {
            PlanBudget::whereId(request()->delete)->delete();

            return view('planbudget');
        } else {

            return view('planbudget', [
                'budgetId' => request()->budgetId,
            ]);
        }
    }

    public function add() {
        if (request()->isClone) {
            $dataset = PlanBudget::whereId(request()->cloneFrom)->first()->dataset;
            $incomes = PlanBudget::whereId(request()->cloneFrom)->first()->incomes;
        } else {
            $dataset = json_encode([]);
            $incomes = json_encode([]);
        }
        $newBudget = PlanBudget::create([
            'month' => request()->month,
            'year' => request()->year,
            'user_id' => Auth::user()->id,
            'dataset' => $dataset,
            'incomes' => $incomes,
        ]);

        return view('planbudget', [
            'budgetId' => $newBudget->id,
        ]);
    }

    public function clone() {

        return view('planbudget', [
        ]);
    }

    public function addItem() {
        $budget = PlanBudget::where('id', request()->currentBudget)->first();
        foreach (json_decode($budget->dataset, true) as $item) {
            $array[] = $item;
        }
        $array[] = [
            'account' => (int) request()->account_id,
            'sum' => (int) request()->sum,
            'order' => (int) request()->order,
        ];

        $budget->dataset = $array;
        $budget->save();

        return view('planbudget', [
            'budgetId' => $budget->id,
        ]);
    }

    public function deleteItem() {
        $budget = PlanBudget::where('id', request()->currentBudget)->first();
        $array = [];
        foreach (json_decode($budget->dataset, true) as $item) {
            if ($item['order'] != request()->id) {
                $array[] = $item;
            }
        }

        $budget->dataset = $array;
        $budget->save();

        return view('planbudget', [
            'budgetId' => $budget->id,
        ]);
    }

    public function addIncome() {
        $budget = PlanBudget::where('id', request()->currentBudget)->first();
        foreach (json_decode($budget->incomes, true) as $item) {
            $array[] = $item;
        }
        $array[] = [
            'account' => (int) request()->account_id,
            'order' => (int) request()->order,
        ];

        $budget->incomes = $array;
        $budget->save();

        return view('planbudget', [
            'budgetId' => $budget->id,
        ]);
    }

    public function deleteIncome() {
        $budget = PlanBudget::where('id', request()->currentBudget)->first();
        $array = [];
        foreach (json_decode($budget->incomes, true) as $item) {
            if ($item['account'] != (int)request()->id) {
                $array[] = $item;
            }
        }

        $budget->incomes = $array;
        $budget->save();

        return view('planbudget', [
            'budgetId' => $budget->id,
        ]);
    }
}
