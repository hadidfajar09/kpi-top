<?php

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\GetDataController;
use App\Http\Controllers\Api\PangkalanController;
use App\Http\Controllers\AuthAgentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('agent/login', [AgentController::class, 'loginAgent']);
Route::post('pangkalan/login', [PangkalanController::class, 'loginPangkalan']);

Route::get('pangkalan', [PangkalanController::class, 'getPangkalan']);
Route::get('agent', [AgentController::class, 'getAgent']);

Route::get('pangkalan/pelanggan', [GetDataController::class, 'getPelanggan']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'ceklevel:1'], function(){
        Route::get('agent', [AgentController::class, 'getAgent']);
        Route::get('agent/getdistribusi', [AgentController::class, 'getDistribusiById']);
        Route::get('agent/slider', [AgentController::class, 'getSliderByAgent']);
        Route::get('agent/pangkalan/id', [AgentController::class, 'getPangkalanById']);
        Route::get('agent/distribusi/id', [AgentController::class, 'getDistribusiByIdPangkalan']);
        Route::get('agent/getpangkalan', [GetDataController::class, 'getPangkalanByAgent']);
        Route::get('agent/distribusi', [AgentController::class, 'getDistribusi']);
        Route::post('agent/status', [AgentController::class, 'ubahStatusByAgent']);
        Route::post('agent/logout', [AgentController::class, 'logout']);
    });
    
    Route::group(['middleware' => 'ceklevel:2'], function(){
        Route::get('pangkalan', [PangkalanController::class, 'getPangkalan']);
        Route::get('pangkalan/slider', [PangkalanController::class, 'getSliderByPangkalan']);
        Route::post('pangkalan/status', [PangkalanController::class, 'ubahStatusByPangkalan']);
        Route::post('pangkalan/scan', [PangkalanController::class, 'scanPelanggan']);
        Route::post('pangkalan/request/tabung', [PangkalanController::class, 'requestTabungByPangkalan']);
        Route::get('pangkalan/distribusi/id', [PangkalanController::class, 'getDistribusiByIdPangkalan']);
        Route::get('pangkalan/transaksi', [PangkalanController::class, 'transaksiByPangkalan']);
        Route::post('pangkalan/logout', [PangkalanController::class, 'logout']);

    });



});




