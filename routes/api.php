<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AccountDetailController;

//Start of Public APIs

// Define the API routes for the 'products' resource
Route::prefix('v1')->group(function () {
    Route::apiResource('products', 'App\Http\Controllers\API\ProductController');



// start of  Account Details Routes
Route::get('account-details', [AccountDetailController::class, 'index']);    // GET all
Route::get('account-details/{recId}', [AccountDetailController::class, 'show']); // GET by ID
Route::post('account-details', [AccountDetailController::class, 'store']);    // POST create
Route::put('account-details/{recId}', [AccountDetailController::class, 'update']); // PUT update
Route::delete('account-details/{recId}', [AccountDetailController::class, 'destroy']); //
Route::post('/update-account-status', [AccountDetailController::class, 'updateAccountStatus']);
Route::get('/get-accounts-by-customer', [AccountDetailController::class, 'getAccountsByCustomer']);


});


// End of Public APIs





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
