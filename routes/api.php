<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::controller('AuthController')->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
    });

    // Route::resource('posts', 'PostController');

    Route::middleware('auth:sanctum')->group(function () {

        Route::controller('ProfileController')->group(function () {
            Route::get('/profile', 'profile');
            Route::post('/profile/store', 'store');
            Route::post('/profile/updatePassword', 'updatePassword');
        });

        Route::controller('OrganizationController')->group(function () {
            Route::get('/organization', 'organization');
            Route::post('/organization/store', 'store');
            Route::get('/defaultTaxPercent', 'defaultTaxPercent');
            Route::get('/defaultTermsCondition', 'defaultTermsCondition');
            Route::get('/defaultCurrency', 'defaultCurrency');
        });

        Route::controller('EmailSettingController')->group(function () {
            Route::get('/smtp', 'smtp');
            Route::post('/smtp/store', 'store');
        });

        Route::controller('SavedItemController')->group(function () {
            Route::get('/saved-items', 'index');
            Route::get('/saved-item/{id}', 'show');
            Route::post('/saved-item/store', 'store');
            Route::post('/saved-item/updateStatus', 'updateStatus');
            Route::post('/saved-item/delete', 'delete');
        });

        Route::controller('CustomerController')->group(function () {
            Route::get('/customers', 'index');
            Route::get('/customer/{id}', 'show');
            Route::post('/customer/store', 'store');
            Route::post('/customer/updateStatus', 'updateStatus');
            Route::post('/customer/delete', 'delete');
        });

        Route::controller('PrefixController')->group(function () {
            Route::get('/prefix', 'prefix');
            Route::post('/prefix/store', 'store');
            Route::get('/prefix/receiptNo', 'generateReceiptNo');
            Route::get('/prefix/quotationNo', 'generateQuotationtNo');
            Route::get('/prefix/invoiceNo', 'generateInvoiceNo');
        });

        Route::controller('ReceiptController')->group(function () {
            Route::get('/receipts', 'index');
            Route::get('/receipt/{id}', 'show');
            Route::post('/receipt/store', 'store');
            Route::post('/receipt/updateStatus', 'updateStatus');
            Route::post('/receipt/delete', 'delete');
            Route::get('/receipt/pdf/{rid}', 'getPDFLink');
            Route::get('/receipt/send/{rid}', 'sendEmail');
        });

        Route::controller('QuotationController')->group(function () {
            Route::get('/quotations', 'index');
            Route::get('/quotation/{id}', 'show');
            Route::post('/quotation/store', 'store');
            Route::post('/quotation/delete', 'delete');
            Route::get('/quotation/pdf/{qid}', 'getPDFLink');
            Route::get('/quotation/send/{qid}', 'sendEmail');
        });

        Route::controller('InvoiceController')->group(function () {
            Route::get('/invoices', 'index');
            Route::get('/invoice/{id}', 'show');
            Route::post('/invoice/store', 'store');
            Route::post('/invoice/delete', 'delete');
            Route::post('/invoice/payment/store', 'storePayment');
            Route::get('/invoice/pdf/{id}', 'getPDFLink');
            Route::get('/invoice/send/{id}', 'sendEmail');
        });

        Route::controller('AccountController')->group(function () {
            Route::get('/accounts', 'index');
            Route::get('/account/{id}', 'show');
            Route::post('/account/store', 'store');
            Route::post('/account/delete', 'delete');
        });

        Route::controller('IncomeExpenseController')->group(function () {
            Route::get('/incomeExpense', 'index');
            Route::get('/incomeExpense/{id}', 'show');
            Route::post('/incomeExpense/store', 'store');
            Route::post('/incomeExpense/delete', 'delete');
        });

        Route::controller('TransferController')->group(function () {
            Route::get('/transfers', 'index');
            Route::get('/transfer/{id}', 'show');
            Route::post('/transfer/store', 'store');
            Route::post('/transfer/delete', 'delete');
        });
        
    });
});