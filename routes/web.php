<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('buy/{cookies}', function ($cookies) {
    $wallet = Auth::user()->wallet;
    $user   = User::where('id',Auth::user()->id)->first();
    
    if($user->wallet < $cookies * 1) 
    {
        return 'You dont have enough balance to buy.';
    }
    $user->update(['wallet' => $wallet - $cookies * 1]);

    // can use this if column name is not specified in fillable property of model
    // $user->forcefill(['wallet' => $wallet - $cookies * 1]);
    // $user->save();

    Log::info('User ' . Auth::user()->email . ' have bought ' . $cookies . ' cookies'); 
    return 'Success, you have bought ' . $cookies . ' cookies!';
})->middleware('auth');
