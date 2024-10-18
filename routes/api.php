<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SadadController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenMiddleware;
use App\Models\Customer;
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
Route::controller(SadadController::class)
->middleware(TokenMiddleware::class)
->prefix('sadad')->group(function(){
    Route::get('get-balance' , 'finance' );
    Route::get('get-biller-type' , 'billerType' );
    Route::get('get-biller-type/{type}' , 'billerInfo' );
    Route::get('get-service-info/{service}' , 'serviceInfo' );
    Route::get('get-service-details/{service}' , 'serviceDetails' );
    Route::post('inquire' , 'inquire' );
    Route::post('pay/{service}' , 'pay' );

});

Route::middleware(TokenMiddleware::class)->group(function () {
    Route::get('get-customer', function(Request $request){
        return Customer::whereMobile($request->search)->first();
    });
    Route::get('/user',  [LoginController::class, 'user']);
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions-count', [TransactionController::class, 'getCount']);
    Route::post('/refresh-token', [LoginController::class, 'refresh']);
    Route::resource('roles', RoleController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('permissions', PermissionController::class);

});



Route::get('/logout', [LoginController::class, 'logout']);


Route::controller(LoginController::class)
    ->group(function () {
        Route::post('/login',  'login');
        Route::get('/change-password/{token}', 'validateToken')->name('password.change');
        Route::post('/change-password/{token}', 'changePassword');
        Route::post('/reset-password', 'resetPassword');
    });
