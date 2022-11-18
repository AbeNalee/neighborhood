<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/search', [\App\Http\Controllers\HomeController::class, 'search'])->name('home.search');
    Route::post('/transact', [\App\Http\Controllers\ProductController::class, 'transact'])->name('product.purchase');
    Route::get('/test', function (){
        return view('welcome');
    });
});
