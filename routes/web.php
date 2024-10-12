<?php

use App\Http\Controllers\AuthContoroller;
use App\Http\Controllers\DashboardContoroller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthContoroller::class, 'index'])->name('login');
Route::post('/login', [AuthContoroller::class, 'login'])->name('authenticated');

Route::middleware(['auth'])->group(function() {
    Route::get('/logout/{id}', [AuthContoroller::class, 'logout'])->name('logout');

    Route::get('/', [DashboardContoroller::class, 'index'])->name('dashboard');

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
        Route::get('/form','form')->name('.form');
        Route::post('/store','store')->name('.store');
        Route::delete('/delete','delete')->name('.delete');
    });
});
