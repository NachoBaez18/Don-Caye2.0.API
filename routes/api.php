<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ArqueoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user'], function () {
    Route::post('login',[UserController::class,'login']);
    Route::post('signup',[UserController::class,'signUp']);   
});

Route::middleware('auth:api')->group(function(){
    Route::post('user', [UserController::class,'user']);
    Route::get('logout',[UserController::class,'logout']);
});

Route::resource('cliente',ClienteController::class);
Route::resource('producto',ProductoController::class);
Route::resource('proveedor',ProveedorController::class);
Route::resource('arqueo',ArqueoController::class);



Route::group(['prefix' => 'pedido'], function () {
    Route::resource('pedidos',PedidosController::class);
 });

 Route::group(['prefix' => 'compra'], function () {
    Route::resource('compras',CompraController::class);
 });
 