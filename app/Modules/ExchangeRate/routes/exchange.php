<?php

use Illuminate\Support\Facades\Route;
use App\Modules\ExchangeRate\Controllers\ExchangeController;

Route::get('exchange/{type}', [ExchangeController::class, 'index']);
