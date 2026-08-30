<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Navbar
Route::get('/', function () {
    return view('navbar.home');
});

Route::get('shop', function () {
    return view('navbar.shop');
});

Route::get('cart', function () {
    return view('navbar.cart');
});

Route::get('contact', function () {
    return view('navbar.contact');
});

Route::get('about', function () {
    return view('navbar.about');
});
//End Navbar




// ---------- Guest only (login မဝင်ရသေးသူများ) ----------
Route::middleware('guest')->group(function () {
    Route::get('register', [UserController::class, 'registerPage'])->name('register');
    Route::post('register', [UserController::class, 'register'])->name('register.store');

    Route::get('login', [UserController::class, 'loginPage'])->name('login');
    Route::post('login', [UserController::class, 'login'])->name('login.store');


    // Admin login ကိုလည်း guest group ထဲ ထည့်ပါ
    Route::get('admin/login', [AdminController::class, 'adminLoginPage'])->name('admin.loginpage');
    Route::post('admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
});

// ---------- Auth ဝင်ပြီးသား user များအတွက် ----------
Route::middleware('auth')->group(function () {
    //    admin and user logout
    Route::get('logout', [UserController::class, 'logout'])->name('admin.logout');

    Route::get('logout', [UserController::class, 'logout'])->name('admin.logout');

    // Profile
    Route::get('profile/edit', [UserController::class, 'profileEditPage'])->name('profile.edit');
    Route::put('profile/edit', [UserController::class, 'profileUpdate'])->name('profile.update');

    // ---------- Admin only (role check ပါ) ----------
    Route::middleware('admin')->group(function () {   // <- custom middleware
        Route::get('admin', [AdminController::class, 'adminDashboard'])->name('admin.store');

        //    ==== User is_active 
        Route::patch('user/{id}/toggle-status', [UserController::class, 'userStatus'])->name('userstatus');
        //    ==== User is_active 

        // ===== adminview user
        Route::get('/users/{id}/view', [UserController::class, 'userDetail'])->name('userdetailpage');
        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::get('user/create', [AdminController::class, 'createPage'])->name('createpage');
        Route::post('user/create', [AdminController::class, 'create'])->name('user.create');
        Route::get('user/edit/{id}', [AdminController::class, 'userUpdatePage'])->name('editpage');
        Route::patch('user/edit/{id}', [AdminController::class, 'userUpdate'])->name('useredit');
        Route::delete('user/{id}', [AdminController::class, 'userDelete'])->name('userdelete');
        // ===== adminview user

        // ==== adminview product

        Route::get('products', [ProductController::class, 'adminProductPage'])->name('admin.productpage');
        Route::get('/admin/product/detail/{id}', [ProductController::class, 'adminProductDetailPage'])->name('admin.productdetailpage');
        Route::get('products/create', [ProductController::class, 'adminProductCreatePage'])->name('admin.productcreatepage');
        Route::post('products/create', [ProductController::class, 'adminProductCreate'])->name('admin.productcreate');
        Route::get('products/edit/{id}', [ProductController::class, 'adminProductEditPage'])->name('admin.producteditpage');
        Route::patch('products/edit/{id}', [ProductController::class, 'adminProductEdit'])->name('admin.productedit');
        Route::delete('products/{id}', [ProductController::class, 'adminProductDelete'])->name('admin.productdelete');
        // ==== adminview product

        // ===== adminview order

        Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orderpage');
        Route::get('/admin/order/detail/{id}', [OrderController::class, 'detail'])->name('admin.orderdetailpage');
        Route::get('/admin/order/create', [OrderController::class, 'createPage'])->name('admin.ordercreatepage');
        Route::post('/admin/order/store', [OrderController::class, 'store'])->name('admin.orderstore');
        Route::patch('/admin/order/status/{id}', [OrderController::class, 'updateStatus'])->name('admin.orderstatus');
        Route::delete('/admin/order/{id}', [OrderController::class, 'destroy'])->name('admin.orderdelete');
        // ===== adminview order

        // ===== Notifications =====
        Route::post('/notifications/{id}/read', function ($id) {
            $user = Auth::user();

            if (!$user) {
                abort(401);
            }

            /** @var \App\Models\User $user */
            $notification = $user->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
            return back();
        })->name('notifications.markAsRead');

        Route::post('/notifications/mark-all-read', function () {
            $user = Auth::user();

            if (!$user) {
                abort(401);
            }

            $user->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.markAllAsRead');
        // ===== End Notifications =====



    });
});
