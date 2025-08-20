<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ================== CLIENT ==================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Client\{
    BlogController,
    CartController,
    ContactController,
    CheckoutController,
    ListProductClientController,
    OrderController,
    ProductDetailController,
    AccountController,
    WishlistController as ClientWishlistController
};

// ================== ADMIN ==================
use App\Http\Controllers\Admin\{
    BannerController,
    CatalogController,
    CategoryController,
    CommentController,
    DashboardController,
    DiscountController,
    OrderController as AdminOrderController,
    PostController,
    ProductController,
    ReviewController,
    UserController as AdminUserController,
    ContactController as AdminContactController
};

// ================== ROUTES ==================

// ---------- CLIENT ----------
Route::prefix('client')->name('client.')->group(function () {
    // Trang chủ
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/san-pham', [ListProductClientController::class, 'index'])->name('listproduct');
    Route::get('/san-pham/{id}', [ProductDetailController::class, 'show'])->name('product.detail');
    Route::get('/san-pham-ban-chay', [HomeController::class, 'bestSellers'])->name('products.best_sellers');
    Route::get('/san-pham-noi-bat', [HomeController::class, 'featured'])->name('products.featured');

    // Đánh giá + bình luận
    Route::post('/san-pham/{id}/danh-gia', [ProductDetailController::class, 'submitReview'])->name('product.review');
    Route::put('/san-pham/{product}/danh-gia/{review}', [ProductDetailController::class, 'updateReview'])->name('product.review.update');
    Route::post('/san-pham/{id}/binh-luan', [ProductDetailController::class, 'submitComment'])->name('product.comment');

    // Liên hệ
    Route::get('/lien-he', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/lien-he', [ContactController::class, 'submit'])->name('contact.submit');

    // Tin tức
    Route::get('/tin-tuc', [BlogController::class, 'index'])->name('listblog');
    Route::get('/tin-tuc/{id}', [BlogController::class, 'show'])->name('listblog.detail');

    // Các chức năng cần đăng nhập
    Route::middleware(['auth'])->group(function () {
        // Giỏ hàng
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add/{productId}', [CartController::class, 'addToCart'])->name('add');
            Route::put('/update-all', [CartController::class, 'updateAll'])->name('updateAll');
            Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        });

        // Wishlist
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [ClientWishlistController::class, 'index'])->name('index');
            Route::post('/add/{id}', [ClientWishlistController::class, 'add'])->name('add');
            Route::delete('/remove/{id}', [ClientWishlistController::class, 'remove'])->name('remove');
        });

        // Thanh toán + đơn hàng
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/don-hang-thanh-cong/{order}', [CheckoutController::class, 'success'])->name('order.success');

        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::middleware(['auth'])->group(function () {
            Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        });
    });
});

// Mini cart
Route::get('/cart/mini', [CartController::class, 'miniCart'])->name('cart.mini');

// Tìm kiếm
Route::get('/search', [ListProductClientController::class, 'index'])->name('client.search');

// Thanh toán Momo
Route::get('/momo_payment', [CheckoutController::class, 'momoPayment'])->name('momo.payment');
Route::get('/momo_return', [CheckoutController::class, 'momoReturn'])->name('momo.return');

// Chatbot
Route::post('/chatbot', [HomeController::class, 'respond']);


// ---------- USER ----------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::post('/update-info', [AccountController::class, 'updateInfo'])->name('updateInfo');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('changePassword');
    Route::post('/save-address', [AccountController::class, 'saveAddress'])->name('saveAddress');
});

// ---------- ADMIN ----------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::get('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/image/{id}', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
    Route::delete('/products/{id}/soft-delete', [ProductController::class, 'softDelete'])->name('products.softDelete');
    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
    Route::delete('/products/delete-selected', [ProductController::class, 'softDeleteSelected'])->name('products.delete.selected');
    Route::resource('products', ProductController::class);

    // Catalog (size, color, danh mục phụ)
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::post('/catalog/size/store', [CatalogController::class, 'storeSize'])->name('catalog.size.store');
    Route::put('/catalog/size/{size}', [CatalogController::class, 'updateSize'])->name('catalog.size.update');
    Route::delete('/catalog/size/{size}', [CatalogController::class, 'destroySize'])->name('catalog.size.destroy');
    Route::post('/catalog/color/store', [CatalogController::class, 'storeColor'])->name('catalog.color.store');
    Route::put('/catalog/color/{color}', [CatalogController::class, 'updateColor'])->name('catalog.color.update');
    Route::delete('/catalog/color/{color}', [CatalogController::class, 'destroyColor'])->name('catalog.color.destroy');

    // Orders
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);
    Route::post('orders/{order}/complete', [AdminOrderController::class, 'completeOrder'])->name('orders.complete');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::put('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');

    // Reviews
    Route::resource('reviews', ReviewController::class);
    Route::get('reviews/trash', [ReviewController::class, 'trash'])->name('reviews.trash');
    Route::post('reviews/restore/{id}', [ReviewController::class, 'restore'])->name('reviews.restore');
    Route::delete('reviews/force-delete/{id}', [ReviewController::class, 'forceDelete'])->name('reviews.forceDelete');

    // Comments
    Route::resource('comments', CommentController::class);
    Route::get('comments/trash', [CommentController::class, 'trash'])->name('comments.trash');
    Route::post('comments/restore/{id}', [CommentController::class, 'restore'])->name('comments.restore');
    Route::delete('comments/force-delete/{id}', [CommentController::class, 'forceDelete'])->name('comments.forceDelete');
    Route::post('comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');

    // Contacts
    Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);

    // Discounts
    Route::resource('discounts', DiscountController::class)->except(['show']);
    Route::get('discounts/export-excel', [DiscountController::class, 'exportExcel'])->name('discounts.exportExcel');
    Route::get('discounts-report', [DiscountController::class, 'report'])->name('discounts.report');
    Route::post('discounts/import-excel', [DiscountController::class, 'importExcel'])->name('discounts.importExcel');
    Route::delete('discounts/bulk-delete', [DiscountController::class, 'bulkDelete'])->name('discounts.bulkDelete');
    Route::get('discounts/{id}', [DiscountController::class, 'show'])->name('discounts.show');

    // Banners
    Route::resource('banners', BannerController::class);

    // Users
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::get('users/{user}/change-password', [AdminUserController::class, 'editPassword'])->name('users.edit-password');
    Route::put('users/{user}/change-password', [AdminUserController::class, 'updatePassword'])->name('users.update-password');
    Route::post('users/{user}/verify-password', [AdminUserController::class, 'verifyPasswordAjax'])->name('users.verify-password.post');

    // Posts
    Route::resource('posts', PostController::class);
});


// ---------- AUTH ----------
Auth::routes();

// ---------- HOME ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
