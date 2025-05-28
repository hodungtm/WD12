<?php
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\WishlistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DiscountsExport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('Admin/test');
});

// Wishlist
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('wishlists', WishlistController::class)->only(['index', 'show', 'destroy']);
});

Route::prefix('admin')->group(function () {
    Route::resource('discounts', DiscountController::class)->except(['show']);
    Route::get('discounts/export-excel', [DiscountController::class, 'exportExcel'])->name('discounts.exportExcel');
    Route::get('discounts-report', [DiscountController::class, 'report'])->name('discounts.report');
});


Route::post('admin/discounts/import-excel', [DiscountController::class, 'importExcel'])->name('discounts.importExcel');
Route::prefix('admin')->group(function () {
    Route::resource('posts', PostController::class);
});
Route::prefix('admin')->name('Admin.')->group(function () {

    // ===== CATEGORIES =====
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::get('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/force-delete/{id}', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

    // ===== REVIEWS =====
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Comment routes
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('admin/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Quản lý tài khoản Admin và Role
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('admins', AdminController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
});
Route::get('admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit_logs.index');




