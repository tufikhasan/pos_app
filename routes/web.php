<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionalMailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleInvoiceController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\StaffsController;
use Illuminate\Support\Facades\Route;

Route::get( '/', function () {
    return view( 'welcome' );
} )->middleware( 'check.token' );

Route::controller( AuthenticationController::class )->group( function () {
    //non authentication route
    Route::middleware( 'check.token' )->group( function () {
        Route::get( '/signup', 'signUpPage' )->name( 'signup.page' );
        Route::post( '/signup', 'signUp' )->name( 'signup' );

        Route::get( '/signin', 'signInPage' )->name( 'signin.page' );
        Route::post( '/signin', 'signIn' )->name( 'signin' );

        Route::get( '/send/otp', 'sendOtpPage' )->name( 'sendOtp.page' );
        Route::post( '/send/otp', 'sendOtp' )->name( 'send.otp' );

        Route::get( '/verify/otp', 'verifyOtpPage' )->name( 'verifyOtp.page' );
        Route::post( '/verify/otp', 'verifyOtp' )->name( 'verify.otp' );
        Route::get( '/countdown/{email}', 'showCountdown' )->name( 'countdown' )->withoutMiddleware( 'check.token' );
    } );

    //reset password
    Route::middleware( 'reset.token' )->group( function () {
        Route::post( '/reset/password', 'resetPassword' )->name( 'reset.password' );
        Route::get( '/reset/password', 'resetPasswordPage' )->name( 'resetPass.page' );
    } );

    //authentication routes
    Route::get( '/signout', 'signout' )->name( 'signout' )->middleware( 'auth.token' );
} );

//authentication routes
Route::middleware( 'auth.token' )->group( function () {
    Route::controller( DashboardController::class )->group( function () {
        Route::get( '/dashboard', 'dashboardView' )->name( 'dashboard' );
    } );

    Route::controller( ProfileController::class )->group( function () {
        Route::get( '/profile', 'profilePage' )->name( 'profile.page' );
        Route::get( '/userNameImage', 'userNameImage' )->name( 'profile.nameImage' );
        Route::get( '/profile/details', 'profileDetails' )->name( 'profile.details' );
        Route::post( '/profile/update', 'profileUpdate' )->name( 'profile.update' );

        Route::get( '/change/password', 'changePassword' )->name( 'change.password' );
        Route::patch( '/change/password', 'updatePassword' )->name( 'password.update' );
    } );

    Route::controller( CustomerController::class )->group( function () {
        //Api Routes
        Route::delete( '/customers/{id}', 'deleteCustomer' )->name( 'delete.customer' )->middleware( 'role:admin' );
        Route::post( '/customers/{id}', 'updateCustomer' )->name( 'update.customer' )->middleware( 'role:admin,manager' );
        Route::get( '/customers/{id}', 'singeCustomer' )->name( 'single.customer' );
        Route::post( '/customers', 'addCustomer' )->name( 'add.customer' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/customers', 'getCustomers' )->name( 'customers' );

        //Page Routes
        Route::get( '/customer/list', 'customerPage' )->name( 'customer.page' );
    } );

    Route::controller( BrandController::class )->group( function () {
        //Api Routes
        Route::delete( '/brands/{id}', 'deleteBrand' )->name( 'delete.brand' )->middleware( 'role:admin' );
        Route::post( '/brands/{id}', 'updateBrand' )->name( 'update.brand' )->middleware( 'role:admin,manager' );
        Route::get( '/brands/{id}', 'singeBrand' )->name( 'single.brand' );
        Route::post( '/brands', 'addBrand' )->name( 'add.brand' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/brands', 'getBrands' )->name( 'brands' );

        //Page Routes
        Route::get( '/brand/list', 'brandPage' )->name( 'brand.page' );
    } );

    Route::controller( CategoryController::class )->group( function () {
        //Api Routes
        Route::delete( '/categories/{id}', 'deleteCategory' )->name( 'delete.category' )->middleware( 'role:admin' );
        Route::post( '/categories/{id}', 'updateCategory' )->name( 'update.category' )->middleware( 'role:admin,manager' );
        Route::get( '/categories/{id}', 'singeCategory' )->name( 'single.category' );
        Route::post( '/categories', 'addCategory' )->name( 'add.category' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/categories', 'getCategories' )->name( 'categories' );

        //Page Routes
        Route::get( '/category/list', 'categoryPage' )->name( 'category.page' );
    } );

    Route::controller( ProductController::class )->group( function () {
        //Api Routes
        Route::delete( '/products/{id}', 'deleteProduct' )->name( 'delete.product' )->middleware( 'role:admin' );
        Route::post( '/products/{id}', 'updateProduct' )->name( 'update.product' )->middleware( 'role:admin,manager' );
        Route::get( '/products/{id}', 'singeProduct' )->name( 'single.product' );
        Route::post( '/products', 'addProduct' )->name( 'add.product' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/products', 'getProducts' )->name( 'products' );

        //Page Routes
        Route::get( '/product/list', 'productPage' )->name( 'product.page' );
    } );

    Route::controller( SaleInvoiceController::class )->group( function () {
        Route::get( '/sale', 'salePage' )->name( 'sale.page' );
        Route::post( '/sale', 'addToCart' )->name( 'add.cart' );
        Route::patch( '/sale/{rowId}', 'updateCartQty' )->name( 'update.cart' );
        Route::delete( '/sale/{rowId}', 'deleteFromCart' )->name( 'remove.cart' );

        Route::post( '/sale/invoice', 'createInvoice' )->name( 'create.invoice' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/invoice/details/{id}', 'invoiceDetails' )->name( 'invoice.details' );
        Route::get( '/invoices', 'invoiceList' )->name( 'invoice.list' );
        Route::get( '/invoice/list', 'allInvoice' )->name( 'all.invoice' );
        Route::delete( '/delete/invoice/{id}', 'deleteInvoice' )->name( 'delete.invoice' )->middleware( 'role:admin' );
    } );

    Route::controller( StaffsController::class )->group( function () {
        Route::get( '/staffs/page', 'staffsPage' )->name( 'staffs.page' );
        Route::get( '/staffs', 'allStaffs' )->name( 'staffs' );
        Route::get( '/staffs/{id}', 'singleStaff' )->name( 'single.staff' );
        Route::post( '/staffs', 'addStaff' )->name( 'add.staff' )->middleware( 'role:admin,manager' );
        Route::patch( '/staffs/{id}', 'updateStaff' )->name( 'update.staff' )->middleware( 'role:admin,manager' );
        Route::delete( '/staffs/{id}', 'deleteStaff' )->name( 'delete.staff' )->middleware( 'role:admin' );
    } );

    Route::controller( ExpenseCategoryController::class )->group( function () {
        //Api Routes
        Route::delete( '/expense/categories/{id}', 'deleteCategory' )->name( 'delete.expense_category' )->middleware( 'role:admin' );
        Route::post( '/expense/categories/{id}', 'updateCategory' )->name( 'update.expense_category' )->middleware( 'role:admin,manager' );
        Route::get( '/expense/categories/{id}', 'singeCategory' )->name( 'single.expense_category' );
        Route::post( '/expense/categories', 'addCategory' )->name( 'add.expense_category' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/expense/categories', 'getCategories' )->name( 'expense.categories' );

        //Page Routes
        Route::get( '/expense/category/list', 'categoryPage' )->name( 'expense_category.page' );
    } );

    Route::controller( ExpenseController::class )->group( function () {
        //Api Routes
        Route::delete( '/expenses/{id}', 'deleteExpense' )->name( 'delete.expense' )->middleware( 'role:admin' );
        Route::patch( '/expenses/{id}', 'updateExpense' )->name( 'update.expense' )->middleware( 'role:admin,manager' );
        Route::get( '/expenses/{id}', 'singeExpense' )->name( 'single.expense' );
        Route::post( '/expenses', 'addExpense' )->name( 'add.expense' )->middleware( 'role:admin,manager,seller' );
        Route::get( '/expenses', 'getExpenses' )->name( 'expenses' );

        //Page Routes
        Route::get( '/expense/list', 'expensePage' )->name( 'expense.page' );
    } );

    Route::controller( ReportController::class )->group( function () {
        Route::get( '/reports', 'getReport' )->name( 'report.page' );
        Route::get( '/sales/report/{fromDate}/{toDate}', 'salesReport' )->name( 'sale.report' );
        Route::get( '/expenses/report/{fromDate}/{toDate}', 'expensesReport' )->name( 'expense.report' );
    } );

    Route::controller( PromotionalMailController::class )->group( function () {
        //Api Routes
        Route::post( '/promotional/mail', 'sendPromotionMail' )->name( 'promotion.mail' )->middleware( 'role:admin,manager' );
        Route::get( '/promotional/mail', 'promotionPage' )->name( 'promotion.page' );
    } );

    Route::controller( SiteSettingController::class )->group( function () {
        Route::get( '/shop/setting', 'shopSettingPage' )->name( 'shop.setting.page' );
        Route::post( '/shop/setting', 'shopUpdate' )->name( 'shop.update' )->middleware( 'role:admin,manager' );;
    } );

} );