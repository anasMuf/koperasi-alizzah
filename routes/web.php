<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\AuthContoroller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardContoroller;
use App\Http\Controllers\ReceivablesController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\SIAKAD\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

Route::get('/login', [AuthContoroller::class, 'index'])->name('login');
Route::post('/login', [AuthContoroller::class, 'login'])->name('authenticated');

Route::middleware(['auth'])->group(function() {
    Route::get('/logout/{id}', [AuthContoroller::class, 'logout'])->name('logout');

    Route::prefix('/')->controller(DashboardContoroller::class)->as('dashboard')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/data','data')->name('.data');
    });

    Route::prefix('/report')->controller(ReportController::class)->as('report')->group(function(){
        Route::get('/','index')->name('.main');
        Route::post('/export','export')->name('.export');
    });

    Route::prefix('/user')->controller(UserController::class)->as('user')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/student')->controller(StudentController::class)->as('student')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/teacher')->controller(TeacherController::class)->as('teacher')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/vendor')->controller(VendorController::class)->as('vendor')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/product')->controller(ProductController::class)->as('product')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/search','search')->name('.search');
        Route::get('/selected','selected')->name('.selected');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/purchase')->controller(PurchaseController::class)->as('purchase')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/new-item','newItem')->name('.new-item');
        Route::post('/store-new-item','storeNewItem')->name('.store-new-item');
        Route::get('/restock','restock')->name('.restock');
        Route::get('/product-variant','productVariant')->name('.product-variant');
        Route::post('/store-restock','storeRestock')->name('.store-restock');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/order')->as('order')->group(function(){
        Route::controller(OrderController::class)->group(function(){
            Route::get('/','index')->name('.main');
            Route::get('/form','form')->name('.form');
            Route::post('/store','store')->name('.store');
            Route::delete('/delete','delete')->name('.delete');
        });
    });

    Route::prefix('/cashier')->controller(CashierController::class)->as('cashier')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/products','getProduct')->name('.products');
        Route::get('/product-variant','productVariant')->name('.product-variant');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/debt')->controller(DebtController::class)->as('debt')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/receivables')->controller(ReceivablesController::class)->as('receivables')->group(function(){
        // siswa
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');

        // other
        Route::get('/member','member')->name('.member');
        Route::get('/createMember','createMember')->name('.createMember');
        Route::post('/newReceivables','newReceivables')->name('.newReceivables');
        Route::get('/paymentMember','paymentMember')->name('.paymentMember');
        Route::post('/payReceivables','payReceivables')->name('.payReceivables');
    });

    Route::prefix('/transaction')->controller(TransactionController::class)->as('transaction')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });

    Route::prefix('/saldo')->controller(SaldoController::class)->as('saldo')->group(function(){
        Route::get('/','index')->name('.main');
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });
});
