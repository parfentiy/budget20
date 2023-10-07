<?php

namespace App\Http\Controllers;

use App\Models\PlanBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    //
    public $sortItems = [
        'noSort' => 'Без сортировки',
        'operation_date' => 'Дата операции',
        'source_account_id' => 'Счет источник',
        'dest_account_id' => 'Счет зачисления',
        'amount' => 'Сумма',
    ];

    public $sortType = [
        'asc' => 'А -> Я',
        'desc' => 'Я -> А',
    ];

    public function showTransactions(Request $request) {
        if ($request->filled('is_set') && $request->is_set) {
            $source_accs = $request->source_accs ??
                \App\Models\CashFlow::where('user_id', Auth::user()->id)
                    ->groupBy('source_account_id')
                    ->pluck('source_account_id');
            $dest_accs = $request->dest_accs ??
                \App\Models\CashFlow::where('user_id', Auth::user()->id)
                    ->groupBy('dest_account_id')
                    ->pluck('dest_account_id');

            $is_set = true;

            $query = \App\Models\CashFlow::where('user_id', Auth::user()->id);

            if ($request->action === 'filterOnMonth') {
                $month = (int)date("m", strtotime(now()));
                $year = (int)date("Y", strtotime(now()));
                $date_start = date('Y-m-d', strtotime($month . '/1/' . $year));
                $date_end = date('Y-m-d',
                    strtotime($month . '/' . cal_days_in_month(CAL_GREGORIAN, $month, $year) . '/' . $year));
            } else {
                $date_start = $request->date_start;
                $date_end = $request->date_end;
            }
            $amount_min = $request->amount_min;
            $amount_max = $request->amount_max;
            if (!is_null($date_start)) $query->whereDate('operation_date', '>=', date($date_start));
            if (!is_null($date_end)) $query->whereDate('operation_date', '<=', date($date_end));
            if (!is_null($amount_min)) $query->where('amount', '>=', $amount_min);
            if (!is_null($amount_max)) $query->where('amount', '<=', $amount_max);
            $query->whereIn('source_account_id', $source_accs);
            $query->whereIn('dest_account_id', $dest_accs);
            $query->orderBy(isset(request()->sort_item) ? request()->sort_item : '', isset(request()->sort_type) ? request()->sort_type : 'asc');

            $transactions = $query->get();
            $amount_sum = $query->pluck('amount')->sum();
        } else {
            $source_accs = \App\Models\CashFlow::where('user_id', Auth::user()->id)
                ->groupBy('source_account_id')
                ->pluck('source_account_id');
            $dest_accs = \App\Models\CashFlow::where('user_id', Auth::user()->id)
                ->groupBy('dest_account_id')
                ->pluck('dest_account_id');
            $is_set = false;
            $date_start = now();
            $date_end = now();
            $amount_min = null;
            $amount_max = null;

            $transactions = \App\Models\CashFlow::where('user_id', Auth::user()->id)->get();
            $amount_sum = \App\Models\CashFlow::where('user_id', Auth::user()->id)
                ->pluck('amount')
                ->sum();
        }

        return view('reports.transactions', [
            'transactions' => $transactions,
            'source_accs' => $source_accs,
            'dest_accs' => $dest_accs,
            'is_set' => $is_set,
            'amount_min' => $amount_min,
            'amount_max' => $amount_max,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'amount_sum' => $amount_sum,
            'sort_items' => $this->sortItems,
            'sort_types' => $this->sortType,
            'sort_item' => request()->sort_item,
            'sort_type' => request()->sort_type,
        ]);
    }

    public function showBudgets() {
        if  (is_null(PlanBudget::where('user_id', Auth::user()->id)->first())) {
            return redirect()->back();
        }
        if (!isset(request()->budgetId)) {

            return view('reports.budgets', [
                'budgetId' => $this->getClosestBudget(),
            ]);
        } else {

            return view('reports.budgets', [
                'budgetId' => request()->budgetId,
            ]);
        }
    }

    private function getClosestBudget() {
        $currentMonth=(int)date("m",strtotime(now()));
        $currentYear=(int)date("Y",strtotime(now()));
        $futureBudgets = PlanBudget::where('user_id', Auth::user()->id)->orderBy('year')->orderBy('month')->get();

        $closestBudget = 0;
        $lastDiff = 1000000000;
        foreach ($futureBudgets as $key => $futureBudget) {
            $datetime1 = date_create($currentYear . '-' . $currentMonth . '-01');
            $datetime2 = date_create($futureBudget->year . '-' . $futureBudget->month . '-01');

            $interval = date_diff($datetime1, $datetime2);
            $diffMonths = $interval->y * 12 + $interval->m;
            if ($diffMonths < $lastDiff) {
                $closestBudget = $futureBudget->id;
                $lastDiff = $diffMonths;
            }
        }

        return $closestBudget;
    }


    public function saveDescription() {
        $budget = PlanBudget::where('id', request()->currentBudget)->first();

        $budget->description = request()->description;
        $budget->save();

        return view('reports.budgets', [
            'budgetId' => request()->currentBudget,
        ]);
    }
}
