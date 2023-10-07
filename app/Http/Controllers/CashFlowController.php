<?php

namespace App\Http\Controllers;

use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use \App\Models\Account;
use \App\Models\CashFlow;
use Illuminate\Support\Facades\Auth;

class CashFlowController extends Controller
{
    //
    public function index() {

        $categories = Account::where('user_id', Auth::user()->id)->where('category', 0)->get();
        $subCategories = Account::where('user_id', Auth::user()->id)->where('category', '!=', 0)->get();

        return view('transactions.new-transaction', [
            'categories' => $categories,
            'subCategories' => $subCategories,
        ]);
    }

    public function create() {
        $message = "Произведена транзакция: \n" .
            "на дату " . date("d.m.Y", strtotime(request()->operation_date)) . "\n" .
            Account::find(request()->source_account_id)->name .
            " -> " . Account::find(request()->dest_account_id)->name . "\n" .
            "на " . request()->amount . " рублей.\n" .
            "Комментарий к расходу: " . request()->description . "\n";
        (new TelegramController)->send($message);

        CashFlow::create([
            'amount' => request()->amount,
            'operation_date' => request()->operation_date,
            'user_id' => Auth::user()->id,
            'description' => request()->description,
            'source_account_id' => request()->source_account_id,
            'dest_account_id' => request()->dest_account_id,
        ]);

        return redirect()->back();
    }

    public function delete() {
        $transaction = CashFlow::whereId(request()->id)->first();
        $message = "УДАЛЕНА транзакция: \n" .
            "на дату " . date("d.m.Y", strtotime($transaction->operation_date)) . "\n" .
            Account::find($transaction->source_account_id)->name .
            " -> " . Account::find($transaction->dest_account_id)->name . "\n" .
            "на " . $transaction->amount . " рублей.\n" .
            "Комментарий к расходу: " . $transaction->description . "\n";
        (new TelegramController)->send($message);

        CashFlow::find(request()->id)->delete();



        return redirect()->back();
    }
}
