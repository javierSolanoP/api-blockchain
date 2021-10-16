<?php

use App\Http\Controllers\BlockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post(uri: '/blocksGenesis/v1', action: [BlockController::class, 'generateGenesis']);
Route::get(uri: '/blocks/v1', action: [BlockController::class, 'getBlocks']);

Route::post(uri: '/blocks/v1', action: [BlockController::class, 'generateBlock']);