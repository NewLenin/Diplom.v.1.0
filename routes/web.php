<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;

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
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index']);
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'authView'])->name('login');
    Route::get('/registration', [RegisterController::class, 'regView'])->name('registration');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/changeUser', [ProfileController::class, 'changeUser'])->name('changeUser');
    Route::get('/profile/showNote', [ProfileController::class, 'showNote'])->name('showNoteProfile');

    Route::get('/diary', [DiaryController::class, 'index'])->name('diary');
    Route::get('/diary/showNote', [DiaryController::class, 'showNote'])->name('showNote');
    Route::post('/diary/addNote', [DiaryController::class, 'addNote'])->name('addNote');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');
    Route::post('/finance/addBill', [FinanceController::class, 'addBill'])->name('addBill');
    Route::get('/finance/deleteBill/{id}', [FinanceController::class, 'deleteBill'])->name('deleteBill');
    Route::post('/finance/deposit', [FinanceController::class, 'deposit'])->name('deposit');
    Route::post('/finance/deduct', [FinanceController::class, 'deduct'])->name('deduct');
    Route::post('/finance/changeColor', [FinanceController::class, 'changeColor'])->name('changeColor');
    Route::get('/finance/diagram', [FinanceController::class, 'diagram'])->name('diagram');



    Route::get('/purpose', [PurposeController::class, 'index'])->name('purpose');
    Route::post('/purpose/addpurpose', [PurposeController::class, 'addPurpose'])->name('addPurpose');
    Route::get('/purpose/deletepurpose/{id}', [PurposeController::class, 'deletePurpose'])->name('deletePurpose');
    Route::post('/purpose/changePurpose', [PurposeController::class, 'changePurpose'])->name('changePurpose');
    Route::post('/purpose/closePurpose', [PurposeController::class, 'closePurpose'])->name('closePurpose');
    Route::post('/purpose/changeTask', [PurposeController::class, 'changeTask'])->name('changeTask');
});
