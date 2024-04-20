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


Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('frontend.home');
Route::post('/search', [App\Http\Controllers\Frontend\HomeController::class, 'search_product'])->name('frontend.search_product');
Route::post('/add_cart', [App\Http\Controllers\Frontend\HomeController::class, 'add_cart'])->name('frontend.add_cart');
Route::get('/cart', [App\Http\Controllers\Frontend\HomeController::class, 'get_cart'])->name('frontend.get_cart');
Route::get('/history', [App\Http\Controllers\Frontend\HomeController::class, 'get_history'])->name('frontend.history');
Route::post('/set_cart', [App\Http\Controllers\Frontend\HomeController::class, 'set_cart'])->name('frontend.set_cart');
Route::post('/remove_cart', [App\Http\Controllers\Frontend\HomeController::class, 'remove_cart'])->name('frontend.remove_cart');
Route::get('/order', [App\Http\Controllers\Frontend\HomeController::class, 'get_order'])->name('frontend.get_order');
Route::post('/create_order', [App\Http\Controllers\Frontend\HomeController::class, 'create_order'])->name('frontend.create_order');
Route::get('/invoice/{oc_number}', [App\Http\Controllers\Frontend\HomeController::class, 'invoice_detail'])->name('frontend.invoice_detail');
Route::get('/inv/pdf/{oc_number}', [App\Http\Controllers\Frontend\HomeController::class, 'download_invoice'])->name('frontend.download_invoice');

Route::post('/nicepay/frontend/nicepay-v1/db-process-url', [App\Http\Controllers\Frontend\NicepayController::class, 'store'])->name('nicepay.store');

Route::middleware(['auth'])->group(function () {
Route::get('/pameran112023haldin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'index'])->name('frontendadmin.home');
Route::post('/searchadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'search_product'])->name('frontendadmin.search_product');
Route::post('/add_cartadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'add_cart'])->name('frontendadmin.add_cart');
Route::get('/cartadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'get_cart'])->name('frontendadmin.get_cart');
Route::post('/set_cartadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'set_cart'])->name('frontendadmin.set_cart');
Route::post('/remove_cartadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'remove_cart'])->name('frontendadmin.remove_cart');
Route::get('/orderadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'get_order'])->name('frontendadmin.get_order');
Route::post('/create_orderadmin', [App\Http\Controllers\Frontend\HomeAdminController::class, 'create_order'])->name('frontendadmin.create_order');
Route::get('/invoiceadmin/{id}', [App\Http\Controllers\Frontend\HomeAdminController::class, 'invoice_detail'])->name('frontendadmin.invoice_detail');
Route::get('/invadmin/pdf/{oc_number}', [App\Http\Controllers\Frontend\HomeAdminController::class, 'download_invoice'])->name('frontendadmin.download_invoice');
});
	


Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth', 'prefix' => 'exhibition'], function(){
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::group(['prefix' => 'akun'], function(){
        Route::get('/user', [App\Http\Controllers\Backend\UserController::class, 'user'])->name('akun.user');
        Route::get('/delete-data/{id}', [App\Http\Controllers\Backend\UserController::class,'delete'])->name('delet');
        Route::get('/profil', [App\Http\Controllers\Backend\UserController::class, 'profil'])->name('akun.profil');
        Route::post('/create', [App\Http\Controllers\Backend\UserController::class, 'create'])->name('akun.create');
        Route::put('/update/{id}', [App\Http\Controllers\Backend\UserController::class, 'update'])->name('akun.update');
        Route::post('/update-profile', [App\Http\Controllers\Backend\UserController::class, 'updateprofile'])->name('update.profile');
        Route::post('/change-password', [App\Http\Controllers\Backend\UserController::class, 'changePassword'])->name('change.password');
    });

    Route::group(['prefix' => 'master'], function(){
        Route::group(['prefix' => 'category'], function(){
            Route::get('/index', [App\Http\Controllers\Backend\CategoryController::class, 'index'])->name('category.index');
            Route::post('/create', [App\Http\Controllers\Backend\CategoryController::class, 'create'])->name('category.create');
        Route::get('/delete-data/{id}', [App\Http\Controllers\Backend\CategoryController::class,'delete'])->name('delete');
        Route::put('/update/{id}', [App\Http\Controllers\Backend\CategoryController::class, 'update'])->name('category.update');
            Route::get('/change_status/{id}', [App\Http\Controllers\Backend\CategoryController::class, 'change_status'])->name('category.change_status');
        });

        Route::group(['prefix' => 'product'], function(){
            Route::get('/index', [App\Http\Controllers\Backend\ProductController::class, 'index'])->name('product.index');
            Route::post('/create', [App\Http\Controllers\Backend\ProductController::class, 'create'])->name('product.create');
            Route::put('/update/{id}', [App\Http\Controllers\Backend\ProductController::class, 'update'])->name('product.update');
            Route::get('/change_status/{id}', [App\Http\Controllers\Backend\ProductController::class, 'change_status'])->name('product.change_status');
            Route::get('/delete-data/{id}', [App\Http\Controllers\Backend\ProductController::class,'delete'])->name('delete.data');

        });

        Route::group(['prefix' => 'history'], function(){
            Route::get('/invoice/{id}', [App\Http\Controllers\Backend\OrderController::class, 'viewinvoice'])->name('order.invoice');
            Route::get('/change_status/{id}', [App\Http\Controllers\Backend\OrderController::class, 'change_status'])->name('order.change_status');
            Route::get('/change_status/{id}/cancel',  [App\Http\Controllers\Backend\OrderController::class, 'cancelStatus'])->name('cancel-status');
            Route::get('/order', [App\Http\Controllers\Backend\OrderController::class, 'index'])->name('order.index');
            Route::get('/order-report', [App\Http\Controllers\Backend\OrderController::class, 'excel'])->name('report.excel');
            Route::post('/report', [App\Http\Controllers\Backend\OrderController::class, 'OrderReport'])->name('reports.excel');
            
        });
    });

});

// Route::get('/PRF-Report', function () {
//         return view('prf.prfreport');
//     })->name('PRFReport')->middleware('auth');
//     Route::post('/PRF-Report-Excel', 'PrfController@PRFReporT')->name('PRFReportExcel')->middleware('auth');