<?php

use App\Http\Controllers\CabaiController;
use App\Http\Controllers\PagesController;
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

Route::get('/', [PagesController::class, 'login']);
Route::post('/verified_login', [PagesController::class, 'verified_login']);
Route::get('/logout', [PagesController::class, 'logout']);


Route::group(['middleware' => ['auth']], function () {
    Route::get('/analisaChen', [PagesController::class, 'analisaChen']);
    Route::get('/analisaCheng', [PagesController::class, 'analisaCheng']);
    Route::post('/import', [CabaiController::class, 'import']);
    Route::resource('/data', CabaiController::class);
});
