<?php

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CatalogController;

use App\Http\Controllers\ProductsController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CommentController;


use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;

use App\Http\Controllers\Admin\WishlistController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\ListProductClientController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::prefix('client')->name('client.')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
    Route::get('/san-pham', [ListProductClientController::class, 'index'])->name('listproduct');
    Route::get('/san-pham/{id}', [ProductDetailController::class, 'show'])->name('product.detail');
    Route::post('/san-pham/{id}/danh-gia', [ProductDetailController::class, 'submitReview'])->name('product.review');
    Route::put('/san-pham/{product}/danh-gia/{review}', [ProductDetailController::class, 'updateReview'])->name('product.review.update');
    Route::post('/san-pham/{id}/binh-luan', [ProductDetailController::class, 'submitComment'])->name('product.comment');
  Route::middleware(['auth'])->group(function () {
        // Giỏ hàng
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index'); // client.cart.index
            Route::post('/add/{productId}', [CartController::class, 'addToCart'])->name('add'); // client.cart.add
            Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('update'); // client.cart.update
            Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove'); // client.cart.remove
        });

        // Thanh toán
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/don-hang-thanh-cong/{order}', [CheckoutController::class, 'success'])->name('order.success');

    });
});

Route::prefix('admin')->group(function () {
    Route::resource('orders', OrderController::class)->names('admin.orders');
});
Route::post('/admin/orders/{order}/complete', [App\Http\Controllers\Admin\OrderController::class, 'completeOrder'])->name('admin.orders.complete');

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


// Quản lý tài khoản
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
});

// Middleware cho trang overview người dùng
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/user/overview', [UserController::class, 'overview'])->name('user.overview');
});


// Auth routes
Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('user/dashboard', [AccountController::class, 'dashboard'])->name('user.dashboard');


////// producst/////////////////////////////////////
Route::prefix('admin')->group(function () {
    Route::resource('products', ProductsController::class);

    // Xóa ảnh phụ
    Route::delete('/products/image/{id}', [ProductsController::class, 'destroyImage'])->name('products.image.destroy');

    // Xóa mềm sản phẩm (dành cho trang danh sách)
    Route::delete('/products/{id}/soft-delete', [ProductsController::class, 'softDelete'])->name('products.softDelete');

    // Danh mục
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

    // Size
    Route::post('/catalog/size/store', [CatalogController::class, 'storeSize'])->name('catalog.size.store');
    Route::put('/catalog/size/{size}', [CatalogController::class, 'updateSize'])->name('catalog.size.update');
    Route::delete('/catalog/size/{size}', [CatalogController::class, 'destroySize'])->name('catalog.size.destroy');

    // Color
    Route::post('/catalog/color/store', [CatalogController::class, 'storeColor'])->name('catalog.color.store');
    Route::put('/catalog/color/{color}', [CatalogController::class, 'updateColor'])->name('catalog.color.update');
    Route::delete('/catalog/color/{color}', [CatalogController::class, 'destroyColor'])->name('catalog.color.destroy');
    
});

Route::get('/search', [App\Http\Controllers\Client\ListProductClientController::class, 'index'])->name('client.search');



