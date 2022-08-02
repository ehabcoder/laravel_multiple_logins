<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
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


Route::get('dashboard', [CustomAuthController::class, 'dashboard']); 
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::get('login/{type}', [CustomAuthController::class, 'dashboard']);
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('checkUserType', [CustomAuthController::class, 'checkUserType'])->name('check-type');

Route::get('registerOrchastra', [CustomAuthController::class, 'regesterOrchastra'])->name('register-orchestra');
Route::get('registerMusician', [CustomAuthController::class, 'registerMusician'])->name('register-musician');
Route::get('registerMember', [CustomAuthController::class, 'registerMember'])->name('register-member');

Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::post('member-registration', [CustomAuthController::class, 'memberRegistration'])->name('register.member'); 
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// send the verification message
Route::get('/email/verify/{id}/{hash}', [CustomAuthController::class, 'emailVerificationVerify'])
->middleware(['auth', 'signed'])->name('verification.verify');

// resend the verification message
Route::post('/email/verification-notification', [CustomAuthController::class, 'resendEmailVerificationLink'])
->middleware(['auth', 'throttle:6,1'])->name('verification.send');