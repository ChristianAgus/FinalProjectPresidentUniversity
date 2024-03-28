<?php

Route::group(['middleware' => 'web'], function() {
    Route::group(['middleware' => ['auth']], function () {
        // Backend
        Route::get('/admin/orders', '\Modules\Orders\Http\Controllers\Backend\OrdersController@index')->name('admin.orders');
        Route::delete('/admin/orders/destroy/{id}', '\Modules\Orders\Http\Controllers\Backend\OrdersController@destroy')->name('admin.orders.destroy');
        Route::delete('/admin/orders/bulk_destroy', '\Modules\Orders\Http\Controllers\Backend\OrdersController@bulk_destroy')->name('admin.orders.bulk_destroy');
        Route::get('/admin/orders/{id}/edit', '\Modules\Orders\Http\Controllers\Backend\OrdersController@edit')->name('admin.orders.edit');
        Route::put('/admin/orders/{id}/update', '\Modules\Orders\Http\Controllers\Backend\OrdersController@update')->name('admin.orders.update');
        Route::get('/admin/orders/{id}/view', '\Modules\Orders\Http\Controllers\Backend\OrdersController@view')->name('admin.orders.view');
        Route::put('/admin/orders/{id}/order-shipping-method', '\Modules\Orders\Http\Controllers\Backend\Orders\OrderShippingMethodController@update')->name('orders.backend.orders.order-shipping-method.update');
        Route::resource('orders/backend/orders/nicepay-v1/enterprise', '\Modules\Orders\Http\Controllers\Backend\Orders\NicepayV1\EnterpriseController', ['as' => 'orders.backend.orders.nicepay-v1'])->only(['index', 'store']);
        Route::resource('orders/backend/orders/nicepay-v1/professional', '\Modules\Orders\Http\Controllers\Backend\Orders\NicepayV1\ProfessionalController', ['as' => 'orders.backend.orders.nicepay-v1'])->only(['index', 'store']);
        Route::get('/admin/orders/download_excel', '\Modules\Orders\Http\Controllers\Backend\OrdersController@download_excel')->name('admin.orders.download_excel');
    });

    // Frontend
    Route::get('/orders/complete-order', '\Modules\Orders\Http\Controllers\Frontend\OrdersController@complete_order')->name('orders.complete_order');
    Route::put('/orders/status/received/{id}', ['as' => 'orders.frontend.orders.status.received.update', 'uses' => '\Modules\Orders\Http\Controllers\Frontend\Orders\Status\ReceivedController@update']);
});
