<?php

use App\Http\Controllers\Api\HouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/houses/search', [HouseController::class, 'search']); 