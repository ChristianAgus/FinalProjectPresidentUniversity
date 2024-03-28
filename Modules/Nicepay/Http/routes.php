<?php

use Modules\Nicepay\Http\Middleware\CheckIpAddressNicepay;

Route::group(['middleware' => 'web'], function () {
    Route::post('nicepay/backend/nicepay-v1/enterprise', [
        'as'   => 'nicepay.backend.nicepay-v1.enterprise.store',
        'uses' => '\Modules\Nicepay\Http\Controllers\Backend\NicepayV1\EnterpriseController@store',
    ]);

    Route::post('nicepay/backend/nicepay-v1/professional', [
        'as'   => 'nicepay.backend.nicepay-v1.professional.store',
        'uses' => '\Modules\Nicepay\Http\Controllers\Backend\NicepayV1\ProfessionalController@store',
    ]);

    Route::get('nicepay/frontend/nicepay-v1/db-call-back-url', [
        'as'   => 'nicepay.frontend.nicepay-v1.db-call-back-url.index',
        'uses' => '\Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\DbCallBackUrlController@index',
    ]);

    Route::post('nicepay/frontend/nicepay-v1/db-process-url', [
        'as'         => 'nicepay.frontend.nicepay-v1.db-process-url.store',
        // 'middleware' => [CheckIpAddressNicepay::class],
        'uses'       => '\Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\DbProcessUrlController@store',
    ]);

    Route::get('nicepay/frontend/nicepay-v1/enterprise/registration-response', [
        'as'   => 'nicepay.frontend.nicepay-v1.enterprise.registration-response',
        'uses' => '\Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\Enterprise\RegistrationResponseController@index',
    ]);
    
    Route::post('nicepay/frontend/nicepay-v1/enterprise/registration-response-ovo', [
        'as'   => 'nicepay.frontend.nicepay-v1.enterprise.registration-response-ovo',
        'uses' => '\Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\Enterprise\RegistrationResponseOvoController@index',
    ]);

    Route::get('nicepay/test-nicepay/{id}', [
        'as'         => 'nicepay.frontend.nicepay-v1.db-process-url.test',
        'uses'       => '\Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\DbProcessUrlController@tesNicepay',
    ]);
});
