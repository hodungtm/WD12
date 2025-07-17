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
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Client\OrderController;


use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CategoryController;


use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Client\ListProductClientController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Client\UserController as ClientUserController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\WishlistController as ClientWishlistController;


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

    // Route::get('/gioi-thieu', [IntroductionController::class, 'index'])->name('gioithieu');


    Route::get('/lien-he', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/lien-he', [ContactController::class, 'submit'])->name('contact.submit');


    Route::get('/tin-tuc', [BlogController::class, 'index'])->name('listblog');
    Route::get('/tin-tuc/{id}', [BlogController::class, 'show'])->name('listblog.detail');
    Route::middleware(['auth'])->group(function () {
        // Giỏ hàng
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add/{productId}', [CartController::class, 'addToCart'])->name('add');
            Route::put('/update-all', [CartController::class, 'updateAll'])->name('updateAll');
            Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        });
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [ClientWishlistController::class, 'index'])->name('index'); // client.wishlist.index
            Route::post('/add/{id}', [ClientWishlistController::class, 'add'])->name('add'); // client.wishlist.add
            Route::delete('/remove/{id}', [ClientWishlistController::class, 'remove'])->name('remove'); // client.wishlist.remove
        });
        // Thanh toán
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/don-hang-thanh-cong/{order}', [CheckoutController::class, 'success'])->name('order.success');
        //Theo dõi đơn hàng
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });
});

Route::get('/momo_payment', [CheckoutController::class, 'momoPayment'])->name('momo.payment');
Route::get('/momo_return', [CheckoutController::class, 'momoReturn'])->name('momo.return');

Route::prefix('admin')->group(function () {
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store'])->names('admin.orders');
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

    Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('discounts', DiscountController::class)->except(['show']);
    Route::get('discounts/export-excel', [DiscountController::class, 'exportExcel'])->name('discounts.exportExcel');
    Route::get('discounts-report', [DiscountController::class, 'report'])->name('discounts.report');

    Route::get('/admin/discounts/{id}', [DiscountController::class, 'show'])->name('discounts.show');
});
Route::post('admin/discounts/import-excel', [DiscountController::class, 'importExcel'])->name('discounts.importExcel');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('banners', BannerController::class);
});


// Quản lý tài khoản
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    // 👉 Thêm 2 route mới để đổi mật khẩu
    Route::get('users/{user}/change-password', [AdminUserController::class, 'editPassword'])->name('users.edit-password');
    Route::put('users/{user}/change-password', [AdminUserController::class, 'updatePassword'])->name('users.update-password');
    Route::post('users/{user}/verify-password', [AdminUserController::class, 'verifyPasswordAjax'])->name('users.verify-password.post');
});

// Middleware cho trang overview người dùng
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/user/overview', [UserController::class, 'overview'])->name('user.overview');
});


Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [ClientUserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/update-info', [ClientUserController::class, 'updateInfo'])->name('user.updateInfo');
    Route::post('/change-password', [ClientUserController::class, 'changePassword'])->name('user.changePassword');
    Route::post('/save-address-session', [ClientUserController::class, 'saveAddressSession'])->name('user.saveAddressSession');
});






// Auth routes
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('user/dashboard', [AccountController::class, 'dashboard'])->name('user.dashboard');


//------------------------------------------------------ producst ------------------------------------------------------>
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/products/trash', [ProductsController::class, 'trash'])->name('trash');
    Route::get('/products/{id}/restore/', [ProductsController::class, 'restore'])->name('restore');

    // Xóa ảnh phụ
    Route::delete('/products/image/{id}', [ProductsController::class, 'destroyImage'])->name('products.image.destroy');

    // Xóa mềm sản phẩm (dành cho trang danh sách)
    Route::delete('/products/{id}/soft-delete', [ProductsController::class, 'softDelete'])->name('products.softDelete');
    Route::delete('/products/{id}/force-delete', [ProductsController::class, 'forceDelete'])->name('products.forceDelete');
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


    Route::delete('/products/delete-selected', [ProductsController::class, 'softDeleteSelected'])->name('products.delete.selected');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductsController::class);
});
//------------------------------------------------------------------------------------------------------------>

// ---------------------------------------ADMIM POST---------------------------------------------------------->
Route::prefix('admin')->group(function () {
    Route::resource('posts', PostController::class);
});
//----------------------------------------------------------------------------------------------------------------------------->

Route::get('/search', [App\Http\Controllers\Client\ListProductClientController::class, 'index'])->name('client.search');

Route::delete('/discounts/bulk-delete', [DiscountController::class, 'bulkDelete'])->name('admin.discounts.bulkDelete');


Route::get('/cart/mini', [\App\Http\Controllers\Client\CartController::class, 'miniCart'])->name('cart.mini');

Route::post('/chatbot', [HomeController::class, 'respond']);
