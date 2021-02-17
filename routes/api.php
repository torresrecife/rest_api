<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\FilesController;
/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group( function () {
    Route::resource('files', FilesController::class);

    Route::get('files/{id}/{data}',
        function(Request $request, $id, $data){
            $controller = app()->make(FilesController::class, array($request));
            return $controller->callAction('details', array($id,$data));
        })
        ->name('files');

    Route::post('files-details/{id}',
        function(Request $request, $id){
            $controller = app()->make(FilesController::class, array($request));
            return $controller->callAction('detailsFile', array($request, $id));
        })
        ->name('files-details');
});
