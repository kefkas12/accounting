<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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
Route::controller(CompanyController::class)->prefix('company')->group(function () {
	Route::get('/', 'index');
	Route::get('/insert', 'detail');
	Route::get('/refresh_akun/{id}', 'refresh_akun');
	Route::post('/insert', 'insert');
	Route::post('/edit/{id}', 'edit');

});