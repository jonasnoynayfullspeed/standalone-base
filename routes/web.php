<?php

use App\Http\Controllers\AuthController;
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

Route::middleware('auth.manage:admin')->group(function () {
    Route::get('/', function () {
        return '<h4><a href="' . route('auth.logout') . '">LOGOUT</a></h4>';
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::get('/signIn', [AuthController::class, 'signIn']);
Route::post('/login', [AuthController::class, 'login']);
