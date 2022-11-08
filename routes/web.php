<?php

use App\Http\Controllers\Admin\ChangeController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Main;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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



//admin
Route::get('/4', function () {
    //return  phpinfo(); 
    User::create(["name" =>  "amir", "phone" => "09004101377", "password" => Hash::make("123")]);
})->name("login.index");
Route::get('/', [Main::class, "index"])->name("login.index");

Route::prefix("admin")->name("admin.")->group(function () {
 
    Route::get('login', [LoginController::class, 'index'])->name("login");
    Route::get('logout', [LoginController::class, 'logout'])->name("logout");
    Route::post('login/attemp', [LoginController::class, 'loginAttemp'])->name("login.attemp");
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name("dashboard");

        Route::resource('currencies', CurrencyController::class);
        Route::resource('changes', ChangeController::class);
        Route::resource('prices', PriceController::class);
       

    });
});
