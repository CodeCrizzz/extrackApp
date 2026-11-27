<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('transactions', TransactionController::class)->except('show');
Route::resource('debts', DebtController::class)->except('show');
Route::resource('categories', CategoryController::class)->except('show');
