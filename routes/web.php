<?php

use App\Exports\DiscountsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\WishlistController;
use App\Http\Controllers\Client\ProductDetailController;


// ->middleware(['auth'])

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});


Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('client/index');
});
Route::get('/test1', function () {
    return view('Client/Product/productDetail');
});
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/san-pham/{id}', [ProductDetailController::class, 'show'])->name('product.detail');
    Route::post('/san-pham/{id}/danh-gia', [ProductDetailController::class, 'submitReview'])->name('product.review');
    Route::post('/san-pham/{id}/binh-luan', [ProductDetailController::class, 'submitComment'])->name('product.comment');
});
Route::prefix('admin')->group(function () {
    Route::resource('orders', OrderController::class)->names('admin.orders');
});
Route::prefix('admin')->name('Admin.')->group(function () {

    // ===== CATEGORIES =====
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::put('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

    // ===== PRODUCTS =====
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.delete');
    Route::get('admin/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // ===== REVIEWS =====
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/trash', [ReviewController::class, 'trash'])->name('reviews.trash');
    Route::post('/reviews/restore/{id}', [ReviewController::class, 'restore'])->name('reviews.restore');
    Route::delete('/reviews/force-delete/{id}', [ReviewController::class, 'forceDelete'])->name('reviews.forceDelete');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ===== COMMENTS =====
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/comments/trash', [CommentController::class, 'trash'])->name('comments.trash');
    Route::post('/comments/restore/{id}', [CommentController::class, 'restore'])->name('comments.restore');
    Route::delete('/comments/force-delete/{id}', [CommentController::class, 'forceDelete'])->name('comments.forceDelete');
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::get('/comments/{id}', [CommentController::class, 'show'])->name('comments.show');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('discounts', DiscountController::class)->except(['show']);
    Route::get('discounts/export-excel', [DiscountController::class, 'exportExcel'])->name('discounts.exportExcel');
    Route::get('discounts-report', [DiscountController::class, 'report'])->name('discounts.report');

    Route::get('discounts/trashed', [DiscountController::class, 'trashed'])->name('discounts.trashed');
    Route::post('discounts/{id}/restore', [DiscountController::class, 'restore'])->name('discounts.restore');
    Route::delete('discounts/delete-all', [DiscountController::class, 'deleteAll'])->name('discounts.deleteAll');
    Route::delete('discounts/{id}/force-delete', [DiscountController::class, 'forceDelete'])->name('discounts.forceDelete');
    Route::get('/admin/discounts/{id}', [DiscountController::class, 'show'])->name('discounts.show');
});Route::post('admin/discounts/import-excel', [DiscountController::class, 'importExcel'])->name('discounts.importExcel');

//// ADMIM POST----------------------------------------------------////////////
Route::prefix('admin')->group(function () {
    Route::resource('posts', PostController::class);
});
Route::delete('/posts/delete-selected', [PostController::class, 'deleteSelected'])->name('posts.delete.selected');
///----------------------------------------------------------------/////////////////

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banners', BannerController::class);
});
// Quản lý tài khoản Admin và Role
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('admins', AdminController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
});
Route::get('admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit_logs.index');



