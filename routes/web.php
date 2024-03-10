<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.index');
});
Route::post('get-company', [
    'as' => 'post.getCompany', 'uses' => 'HomeController@getCompany'
]);
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/login', 'Auth\AdminLoginController@index')->name('admin');
Route::post('admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::post('admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

Route::get('admin/forgot-password', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
Route::post('admin/forgot-password', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');

// Route::group(['middleware' => ['verified','auth']], function () {
    Route::group(['prefix' => 'admin','middleware' => ['admin-access']], function () {
        Route::get('/', 'Admin\AdminController@dashboard')->name('admin.index');
       Route::get('/chart','Admin\InvoiceController@salesChart')->name('admin.chart');
        
        Route::post('/filter', 'Admin\AdminController@filter')->name('admin.filter');

        // Company Management
        Route::get('company','Admin\CompanyComtroller@index')->name('admin.company');
        Route::get('company/add','Admin\CompanyComtroller@add')->name('admin.company.add');
        Route::post('company/store','Admin\CompanyComtroller@store')->name('admin.company.store');
        Route::post('company/get-ajax-data','Admin\CompanyComtroller@ajaxData')->name('admin.company.filter');
        Route::get('company/{action}/{id}','Admin\CompanyComtroller@view')->name('admin.company.view');
        Route::post('company/delete','Admin\CompanyComtroller@delete')->name('admin.company.delete');
        Route::get('company/bank/add-bank-details/{companyId}','Admin\CompanyComtroller@addBankDetails')->name('admin.company.add-bank-details');
        Route::get('company/bank/edit-bank-details/{companyId}/{id}','Admin\CompanyComtroller@addBankDetails')->name('admin.company.edit-bank-details');
        Route::get('company/bank/delete-bank-details/{companyId}/{id}','Admin\CompanyComtroller@deleteBankDetails')->name('admin.company.delete-bank-details');
        Route::post('company/bank-details','Admin\CompanyComtroller@storeBankDetails')->name('admin.company.bank-details.add');
        // End Company Management

         // Vendor Management
        Route::get('vendor','Admin\VendorController@index')->name('admin.vendor');
        Route::get('vendor/add','Admin\VendorController@add')->name('admin.vendor.add');
        Route::post('vendor/store','Admin\VendorController@store')->name('admin.vendor.store');
        Route::post('vendor/get-ajax-data','Admin\VendorController@ajaxData')->name('admin.vendor.filter');
        Route::get('vendor/{action}/{id}','Admin\VendorController@view')->name('admin.vendor.view');
        Route::post('vendor/delete','Admin\VendorController@delete')->name('admin.vendor.delete');
        Route::get('vendor/bank/add-bank-details/{vendorId}','Admin\VendorController@addBankDetails')->name('admin.vendor.add-bank-details');
        Route::get('vendor/bank/edit-bank-details/{vendorId}/{id}','Admin\VendorController@addBankDetails')->name('admin.vendor.edit-bank-details');
        Route::get('vendor/bank/delete-bank-details/{vendorId}/{id}','Admin\VendorController@deleteBankDetails')->name('admin.vendor.delete-bank-details');
        Route::post('vendor/bank-details','Admin\VendorController@storeBankDetails')->name('admin.vendor.bank-details.add');
        Route::post('fetch-state','Admin\VendorController@fetchState')->name('fetch-state');
        // End Vendor Management

        // User Management
        Route::get('user','Admin\UserController@index')->name('admin.user');
        Route::get('user/add','Admin\UserController@add')->name('admin.user.add');
        Route::post('user/store','Admin\UserController@store')->name('admin.user.store');
        Route::post('user/get-ajax-data','Admin\UserController@ajaxData')->name('admin.user.filter');
        Route::get('user/{action}/{id}','Admin\UserController@view')->name('admin.user.view');
        Route::post('user/delete','Admin\UserController@delete')->name('admin.user.delete');
        // End User Management

        // Production Management
        Route::get('production','Admin\ProductionController@index')->name('admin.production');
        Route::get('production/add','Admin\ProductionController@add')->name('admin.production.add');
        Route::post('production/store','Admin\ProductionController@store')->name('admin.production.store');
        Route::post('production/get-ajax-data','Admin\ProductionController@ajaxData')->name('admin.production.filter');
        Route::get('production/{action}/{id}','Admin\ProductionController@view')->name('admin.production.view');
        Route::post('production/delete','Admin\ProductionController@delete')->name('admin.production.delete');
        Route::post('fetch-product-materials','Admin\ProductionController@fetchProductMaterial')->name('admin.fetch_product_material');
        Route::post('production-units','Admin\ProductionController@getUnits')->name('admin.getunits'); //use for production get unit 
        
        // End Production Management

        // Product Management
        Route::get('product','Admin\ProductController@index')->name('admin.product');
        Route::get('product/add','Admin\ProductController@add')->name('admin.product.add');
        Route::post('product/store','Admin\ProductController@store')->name('admin.product.store');
        Route::post('product/get-ajax-data','Admin\ProductController@ajaxData')->name('admin.product.filter');
        Route::get('product/{action}/{id}','Admin\ProductController@view')->name('admin.product.view');
        Route::post('product/delete','Admin\ProductController@delete')->name('admin.product.delete');
        // End Product Management

        // Product Type Management
        Route::get('product-type','Admin\ProductTypeController@index')->name('admin.product_type');
        Route::get('product-type/add','Admin\ProductTypeController@add')->name('admin.product_type.add');
        Route::post('product-type/store','Admin\ProductTypeController@store')->name('admin.product_type.store');
        Route::post('product-type/get-ajax-data','Admin\ProductTypeController@ajaxData')->name('admin.product_type.filter');
        Route::get('product-type/{action}/{id}','Admin\ProductTypeController@view')->name('admin.product_type.view');
        Route::post('product-type/delete','Admin\ProductTypeController@delete')->name('admin.product_type.delete');
        // End Product Type Management

        // Perfoma Invoice Management
        Route::get('perfoma-invoice','Admin\PIController@index')->name('admin.perfoma-invoice');
        Route::get('perfoma-invoice/add','Admin\PIController@add')->name('admin.perfoma-invoice.add');
        Route::post('perfoma-invoice/store','Admin\PIController@store')->name('admin.perfoma-invoice.store');
        Route::post('perfoma-invoice/get-ajax-data','Admin\PIController@ajaxData')->name('admin.perfoma-invoice.filter');
        Route::get('perfoma-invoice/{action}/{id}','Admin\PIController@view')->name('admin.perfoma-invoice.view');
        Route::post('perfoma-invoice/delete','Admin\PIController@delete')->name('admin.perfoma-invoice.delete');
        Route::post('perfoma-invoice/approve','Admin\PIController@approve')->name('admin.perfoma-invoice.approve');
        Route::post('perfoma-invoice/reject','Admin\PIController@reject')->name('admin.perfoma-invoice.reject');
        Route::post('fetch-bank-accounts','Admin\PIController@fetchBankAccounts')->name('fetch-bank-accounts');
        Route::get('perfoma-invoice/download/invoice/{id}','Admin\PIController@downloadInvoice')->name('admin.perfoma-invoice.download');
        // End Product Management

        // Purchase Order Management
        Route::get('purchase-order','Admin\POController@index')->name('admin.purchase-order');
        Route::get('purchase-order/add','Admin\POController@add')->name('admin.purchase-order.add');
        Route::post('purchase-order/store','Admin\POController@store')->name('admin.purchase-order.store');
        Route::post('purchase-order/get-ajax-data','Admin\POController@ajaxData')->name('admin.purchase-order.filter');
        Route::get('purchase-order/{action}/{id}','Admin\POController@view')->name('admin.purchase-order.view');
        Route::post('purchase-order/delete','Admin\POController@delete')->name('admin.purchase-order.delete');
        Route::post('purchase-order/approve','Admin\POController@approve')->name('admin.purchase-order.approve');
        Route::post('purchase-order/reject','Admin\POController@reject')->name('admin.purchase-order.reject');
        Route::get('purchase-order/download/invoice/{id}','Admin\POController@downloadInvoice')->name('admin.purchase-order.download');
        //Route::post('fetch-bank-accounts','Admin\POController@fetchBankAccounts')->name('fetch-bank-accounts');
        // End Purchase Order Management

        //Invoice Management
        Route::get('invoice','Admin\InvoiceController@index')->name('admin.invoice');
        Route::get('invoice/add','Admin\InvoiceController@add')->name('admin.invoice.add');
        Route::post('invoice/store','Admin\InvoiceController@store')->name('admin.invoice.store');
        Route::post('invoice/get-ajax-data','Admin\InvoiceController@ajaxData')->name('admin.invoice.filter');
        Route::get('invoice/{action}/{id}','Admin\InvoiceController@view')->name('admin.invoice.view');
        Route::post('invoice/delete','Admin\InvoiceController@delete')->name('admin.invoice.delete');
        Route::post('invoice/approve','Admin\InvoiceController@approve')->name('admin.invoice.approve');
        Route::post('invoice/reject','Admin\InvoiceController@reject')->name('admin.invoice.reject');
        Route::get('invoice/download/invoice/{id}','Admin\InvoiceController@downloadInvoice')->name('admin.invoice.download');
        Route::post('invoice/fetch-perfoma-details','Admin\InvoiceController@fetchPerfomaDetails')->name('admin.invoice.fetch-perfoma-details');
        //End Invoice Management

        //Purchase Management
        Route::get('purchase','Admin\PrchaseController@index')->name('admin.purchase');
        Route::get('purchase/add','Admin\PrchaseController@add')->name('admin.purchase.add');
        Route::post('purchase/store','Admin\PrchaseController@store')->name('admin.purchase.store');
        Route::post('purchase/get-ajax-data','Admin\PrchaseController@ajaxData')->name('admin.purchase.filter');
        Route::get('purchase/{action}/{id}','Admin\PrchaseController@view')->name('admin.purchase.view');
        Route::post('purchase/delete','Admin\PrchaseController@delete')->name('admin.purchase.delete');
        Route::post('purchase/approve','Admin\PrchaseController@approve')->name('admin.purchase.approve');
        Route::post('purchase/reject','Admin\PrchaseController@reject')->name('admin.purchase.reject');
        Route::get('purchase/download/invoice/{id}','Admin\PrchaseController@downloadInvoice')->name('admin.purchase.download');
        Route::post('purchase/fetch-purchase-details','Admin\PrchaseController@fetchPurchaseDetails')->name('admin.purchase.fetch-purchase-details');
        //End Purchase Management

        //Payment Management
        Route::get('payment','Admin\PaymentController@index')->name('admin.payment');
        Route::get('payment/add','Admin\PaymentController@add')->name('admin.payment.add');
        Route::post('payment/store','Admin\PaymentController@store')->name('admin.payment.store');
        Route::post('payment/get-ajax-data','Admin\PaymentController@ajaxData')->name('admin.payment.filter');
        Route::get('payment/{action}/{id}','Admin\PaymentController@view')->name('admin.payment.view');
        Route::post('payment/delete','Admin\PaymentController@delete')->name('admin.payment.delete');
        Route::post('payment/approve','Admin\PaymentController@approve')->name('admin.payment.approve');
        Route::post('payment/reject','Admin\PaymentController@reject')->name('admin.payment.reject');
        //End Payment Management

        //Receipt Management
        Route::get('receipt','Admin\ReceiptController@index')->name('admin.receipt');
        Route::get('receipt/add','Admin\ReceiptController@add')->name('admin.receipt.add');
        Route::post('receipt/store','Admin\ReceiptController@store')->name('admin.receipt.store');
        Route::post('receipt/get-ajax-data','Admin\ReceiptController@ajaxData')->name('admin.receipt.filter');
        Route::get('receipt/{action}/{id}','Admin\ReceiptController@view')->name('admin.receipt.view');
        Route::post('receipt/delete','Admin\ReceiptController@delete')->name('admin.receipt.delete');
        Route::post('receipt/approve','Admin\ReceiptController@approve')->name('admin.receipt.approve');
        Route::post('receipt/reject','Admin\ReceiptController@reject')->name('admin.receipt.reject');
        //End Receipt Management

        //Expense Management
        Route::get('expense','Admin\ExpenseController@index')->name('admin.expense');
        Route::get('expense/add','Admin\ExpenseController@add')->name('admin.expense.add');
        Route::post('expense/store','Admin\ExpenseController@store')->name('admin.expense.store');
        Route::post('expense/get-ajax-data','Admin\ExpenseController@ajaxData')->name('admin.expense.filter');
        Route::get('expense/{action}/{id}','Admin\ExpenseController@view')->name('admin.expense.view');
        Route::post('expense/delete','Admin\ExpenseController@delete')->name('admin.expense.delete');
        Route::post('expense/approve','Admin\ExpenseController@approve')->name('admin.expense.approve');
        Route::post('expense/reject','Admin\ExpenseController@reject')->name('admin.expense.reject');
        //End Expense Management


        // Raw Material Management
        Route::resource('raw-material', 'Admin\RawMaterialController')->except(['update']);
        Route::post('raw-material-list', 'Admin\RawMaterialController@ajaxData')->name('raw-material-list');
        Route::post('raw-material/delete','Admin\RawMaterialController@destroy')->name('admin.raw-material.delete');
    });

        Route::get('testpage','TestingController@testpage');
// });
