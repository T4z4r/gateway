<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountMarkingController;
use App\Http\Controllers\API\AccountDetailController;
use App\Http\Controllers\API\AccountMergingController;

//Start of Public APIs

// Define the API routes for the 'products' resource
Route::prefix('v1')->group(function () {
    Route::apiResource('products', 'App\Http\Controllers\API\ProductController');



    // start of  Account Details Routes
    Route::get('account-details/{cuId}', [AccountDetailController::class, 'show']); // GET by ID
    Route::post('account-details', [AccountDetailController::class, 'store']);    // POST create
    Route::put('account-details/{cuId}', [AccountDetailController::class, 'update']); // PUT update
    Route::delete('account-details/{cuId}', [AccountDetailController::class, 'destroy']); //
    Route::post('/update-account-status', [AccountDetailController::class, 'updateAccountStatus']);
    Route::get('/get-accounts-by-customer', [AccountDetailController::class, 'getAccountsByCustomer']);


    Route::get('account-merging/index', [AccountMergingController::class, 'index']);
    Route::post('account-merging/store', [AccountMergingController::class, 'store']);
    Route::post('account-merging/process', [AccountMergingController::class, 'processAccountMerge']);

    Route::post('account-merging/approve/{id}', [AccountMergingController::class, 'approve']);
    Route::post('account-merging/reject/{id}', [AccountMergingController::class, 'reject']);


    Route::post('account-marking-requests', [AccountMarkingController::class, 'store']);
    Route::get('account-marking-requests', [AccountMarkingController::class, 'index']);

});


// End of Public APIs

//start of COP360 based APIs
Route::prefix('v1/cop360')->group(function () {
    //Filter either NTB /ETB use type search for filtering
    Route::get('customerPool', [AccountDetailController::class, 'customerPool']);    // GET all
    Route::get('customerPool/{type}', [AccountDetailController::class, 'accountTypeList']);    // GET all
    // Route::get('customerPool', [AccountDetailController::class, 'mulitpleCustomerIDs']);    // GET all



});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
