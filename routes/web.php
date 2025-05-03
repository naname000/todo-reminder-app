<?php

use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProfileController;
use App\Models\Operation;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Route::get('/dashboard', function () {
    // 今日のOperationを取得
    $today_operations = Operation::getTodayOperations();
    // 次営業日のOperationを取得
    $tomorrow_operations = Operation::getNextBusinessDayOperations();
    return Inertia::render('Dashboard', [
        'today_operations' => $today_operations,
        'next_business_day_operations' => $tomorrow_operations,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('operations', OperationController::class)->only([
    'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
])->middleware('auth');


require __DIR__.'/auth.php';
