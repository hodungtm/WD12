<?php
use App\Http\Controllers\Admin\DiscountController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('Admin/test');
});


Route::prefix('admin')->group(function () {
    Route::resource('discounts', DiscountController::class);
    Route::get('discounts-report', [DiscountController::class, 'report'])->name('discounts.report');
});
