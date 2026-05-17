<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RcaSimpleAuthController;
use App\Http\Controllers\RcaOfferController;

Route::get('/', function () {
    return redirect()->route('rca.login');
});

Route::get('/rca-login', [RcaSimpleAuthController::class, 'showLogin'])
    ->name('rca.login');

Route::post('/rca-login', [RcaSimpleAuthController::class, 'login'])
    ->name('rca.login.submit');

Route::post('/rca-logout', [RcaSimpleAuthController::class, 'logout'])
    ->name('rca.logout');

Route::middleware('rca.auth')->group(function () {

    Route::get('/rca-dashboard', function () {
            return view('rca.dashboard');
        })->name('rca.dashboard');
        
    Route::get('/rca-offer', [RcaOfferController::class, 'create'])
        ->name('rca.offer.create');

    Route::post('/rca-offer', [RcaOfferController::class, 'store'])
        ->name('rca.offer.store');

    Route::get('/rca-offer/{offerId}/download', [RcaOfferController::class, 'downloadOfferPdf'])
        ->name('rca.offer.download');

    Route::post('/rca-offer/{offerId}/policy', [RcaOfferController::class, 'transformToPolicy'])
        ->name('rca.offer.policy');

    Route::get('/rca-policy/{policyId}/download', [RcaOfferController::class, 'downloadPolicyPdf'])
        ->name('rca.policy.download');
});
