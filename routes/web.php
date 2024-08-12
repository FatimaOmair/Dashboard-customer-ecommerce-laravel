<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\Dashboard\BannerController;
use App\Http\Controllers\Dashboard\CartController as DashboardCartController;
use App\Http\Controllers\Dashboard\SliderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth', 'redirect.if.authenticated'])->group(function () {
    Route::get('/dashboard', function () {
    });


    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

});

Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth');

// Authentication routes
Auth::routes();

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Auth::routes();






Route::middleware('auth')->group(function(){
    Route::get('/my-account', [UserController::class,'index'])->name('user.index');

});

    Route::middleware('auth','auth.admin')->group(function(){
        Route::get('/admin', [AdminController::class,'index'])->name('admin.index');

    });



    Route::get('/dash',function(){return view('dashboard');})->middleware(['auth']);


    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('sliders', SliderController::class);
            Route::resource('banners', BannerController::class);
    });


    Route::resource('categories', CategoriesController::class);
    Route::get('categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}', [CategoriesController::class, 'show'])->name('categories.show');
    Route::get('categories/trash', [CategoriesController::class, 'trash'])->name('categories.trash');
    Route::patch('categories/restore/{id}', [CategoriesController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/forceDelete/{id}', [CategoriesController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
    Route::get('categories/{category}/products', [CategoriesController::class, 'products'])->name('categories.products');


    Route::resource('products', ProductsController::class);
    Route::get('products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductsController::class, 'show'])->name('products.show');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');




    Route::get('/app', [AppController::class, 'index'])->name('app.index');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/product/{slug}', [ShopController::class, 'productDetails'])->name('shop.product.details');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'add'])->name('cart.store');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


    Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/carts/create', [CartController::class, 'create'])->name('carts.create');
        Route::resource('/carts', CartController::class)->except(['index', 'create']);
        Route::get('/carts', [DashboardCartController::class, 'viewAllCarts'])->name('carts.index');
    });


Route::middleware('auth')->group(function(){
Route::get('/my-account', [UserController::class,'index'])->name('user.index');

});
Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard')->middleware('auth');

Route::middleware(['auth', 'role:user','redirect.if.authenticated'])->group(function () {


});










