<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PlanBudgetController;
use App\Http\Controllers\GodController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TelegramController;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\Setting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('mainpage');
})->middleware(['auth', 'verified'])->name('mainpage');

Route::get('/tbot', [TelegramController::class, 'getMe']);
Route::get('/tbot/setwebhook', [TelegramController::class, 'setWebHook']);
Route::post('/webhook', [TelegramController::class, 'getFromBot']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/transaction', [CashFlowController::class, 'index'])->name('transaction');
    Route::post('/transaction/new', [CashFlowController::class, 'create'])->name('transaction.new');
    Route::post('/transaction/delete', [CashFlowController::class, 'delete'])->name('transaction.delete');


    Route::get('/refbooks', function () {
        return view('refbooks');
    })->name('refbooks');
    Route::get('/refbooks/accounts', [AccountController::class, 'show'])->name('account.show');
    Route::post('/refbooks/accounts/update', [AccountController::class, 'update'])->name('account.update');
    Route::post('/refbooks/accounts/delete', [AccountController::class, 'delete'])->name('account.delete');
    Route::post('/refbooks/accounts/create', [AccountController::class, 'create'])->name('account.create');

    Route::get('/reports', function () {
        return view('reports.reports-list');
    })->name('reports.list');
    Route::get('/reports/transactions', [ReportsController::class, 'showTransactions'])->name('reports.transactions');
    Route::post('/reports/transactions', [ReportsController::class, 'showTransactions'])->name('post.reports.transactions');
    Route::get('/reports/budgets', [ReportsController::class, 'showBudgets'])->name('reports.budgets');
    Route::post('/reports/budgets', [ReportsController::class, 'showBudgets'])->name('post.reports.budgets');

    Route::get('/budget-planning', [PlanBudgetController::class, 'index']);
    Route::post('/budget-planning', [PlanBudgetController::class, 'index']);
    Route::post('/budget-planning/add', [PlanBudgetController::class, 'add'])->name('planbudget.add');
    Route::post('/budget-planning/edit', [PlanBudgetController::class, 'index'])->name('planbudget.edit');
    Route::get('/budget-planning/edit', [PlanBudgetController::class, 'index']);

    Route::post('/budget-planning/add-item', [PlanBudgetController::class, 'addItem'])->name('planbudget.addItem');
    Route::get('/budget-planning/add-item', [PlanBudgetController::class, 'addItem']);
    Route::post('/budget-planning/delete-item', [PlanBudgetController::class, 'deleteItem'])->name('planbudget.deleteItem');
    Route::get('/budget-planning/delete-item', [PlanBudgetController::class, 'deleteItem']);

    Route::post('/budget-planning/clone-budget', [PlanBudgetController::class, 'clone'])->name('planbudget.clone');

    Route::post('/budget-planning/add-income', [PlanBudgetController::class, 'addIncome'])->name('planbudget.addIncome');
    Route::get('/budget-planning/add-income', [PlanBudgetController::class, 'addIncome']);
    Route::post('/budget-planning/delete-income', [PlanBudgetController::class, 'deleteIncome'])->name('planbudget.deleteIncome');
    Route::get('/budget-planning/delete-income', [PlanBudgetController::class, 'deleteIncome']);

    Route::post('/reports/save-description', [ReportsController::class, 'saveDescription'])
        ->name('addDescription');

    Route::get('/god', function () {
       return view('god-mainpage');
    });
    Route::get('/god/users', [GodController::class, 'showUsers']);
    Route::post('/god/users/change-ban', [GodController::class, 'changeBanStatus'])->name('changeUserBan');
    Route::post('/god/users/user-delete', [GodController::class, 'deleteUser'])->name('user.delete');

    Route::get('/settings', function () {
        $settings = Setting::where('user_id', Auth::user()->id)->first();
        return view('settings', ['settings' => $settings]);
    })->name('settings');
    Route::post('/settings/save' , [SettingController::class, 'save'])->name('settings.save');

});

require __DIR__.'/auth.php';
