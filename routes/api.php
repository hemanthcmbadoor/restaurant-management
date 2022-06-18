<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\AdminController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/mail', function () {
//     return view('mail.forgot_mail');
// });

Route::post('/user/signup',[UserController::class,'register']);
Route::post('/user/login',[UserController::class,'login']);
Route::post('/user/forgotpassword',[UserController::class, 'forgotPassword']);
Route::post('/user/changepassword',[UserController::class, 'changePassword']);


Route::group(['middleware' => ['auth:sanctum','user.account']], function () {

    Route::get('/user/get',[UserController::class, 'getUserList']); 
    Route::patch('/user/update',[UserController::class, 'updateUserStatus']);
    Route::get('/checkToken',[UserController::class, 'checkToken']);

    Route::get('/category/get',[CategoryController::class, 'get']);
    
    Route::post('/bill/generaterepost',[BillController::class, 'generateReport']);
    Route::post('/bill/getpdf',[BillController::class, 'downloadPdf']);
    Route::get('/bill/getbill',[BillController::class, 'getBill']);
    Route::delete('/bill/delete/{id}',[BillController::class, 'delete']);

    Route::get('/admin/details', [AdminController::class, 'getDetails']);

});


Route::group(['middleware' => ['auth:sanctum','role','user.account']], function () {

    Route::post('/category/add',[CategoryController::class, 'add']);
    Route::patch('/category/update',[CategoryController::class, 'update']);

    Route::post('/product/add',[ProductController::class, 'add']);
    Route::get('/product/get',[ProductController::class, 'get']);
    Route::get('/product/getbycategory/{id}',[ProductController::class, 'getByCategory']); 
    Route::get('/product/getbyid/{id}',[ProductController::class, 'getById']);
    Route::patch('/product/update',[ProductController::class, 'update']);
    Route::patch('/product/updatestatus',[ProductController::class, 'updateStatus']);
    Route::delete('/product/delete/{id}',[ProductController::class, 'delete']);

});

