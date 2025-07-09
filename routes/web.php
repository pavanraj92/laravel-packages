<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WizardController;

Route::middleware('installer.check')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/wizard-install', [WizardController::class, 'index'])->name('wizard-install');
    Route::post('/store-industry', [WizardController::class, 'storeIndustry'])->name('store-industry');
    Route::post('/create-database', [WizardController::class, 'createDatabase'])->name('create-database');
    Route::post('/store-packages', [WizardController::class, 'storePackages'])->name('store-packages');
    Route::get('/clear-session', [WizardController::class, 'clearSession'])->name('clearSession');
    Route::get('/run-migration', [WizardController::class, 'runMigration'])->name('runMigration');

});

Route::post('/store-admin-credentails', [WizardController::class, 'storeAdmin'])->name('store-admin-credentails');
Route::get('/thank-you', [WizardController::class, 'viewThankYouPage'])->name('thankyou');

Route::post('/check-package-installed', [WizardController::class, 'checkPackageInstalled'])->name('check.package');
