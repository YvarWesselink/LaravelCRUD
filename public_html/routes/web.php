<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\dataForUser;
use App\Http\Controllers\dataForAdmin;
use App\Http\Middleware\userIsAdmin;
use App\Http\Middleware\CheckBanned;
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

Route::get('/', function () {
    return view('welcome');
})->middleware(userIsAdmin::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware([CheckBanned::class,'auth', 'verified'])->name('dashboard');

Route::get('/info', [dataForAdmin::class, 'fetchAllUsers'])->middleware([CheckBanned::class,'auth', 'verified' ])->name('info'); 
Route::post('/updateInfo/{id}', [dataForAdmin::class, 'updateInfoUser'])->middleware([CheckBanned::class,'auth', 'verified' ])->name('updateInfo');
Route::get('/acount/{id}', function () {
    return view('acount');
})->middleware(['auth',CheckBanned::class, 'verified'])->name('acount/{id}');
Route::post('/acount/{id}/userInfo', [dataForUser::class, 'userInfo'])->middleware([CheckBanned::class,'auth', 'verified' ])->name('userInfo');
Route::get('/acount/{id}/', [dataForUser::class, 'fetchUserInfo'])->middleware([CheckBanned::class,'auth', 'verified' ])->name('fetchUserInfo'); 




Route::middleware(CheckBanned::class, 'auth' )->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
