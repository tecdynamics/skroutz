<?php

Route::group(['namespace' => 'Tec\Skroutz\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::post('skroutz/savesettings', [
            'as' => 'skroutz.savesettings',
            'uses' => 'SkroutzController@saveform',
        ]);
        Route::get('skroutz/runmanual', [
            'as' => 'skroutz.runmanual',
            'uses' => 'SkroutzController@runManual',
        ]);
        Route::group(['prefix' => 'skroutz', 'as' => 'skroutz.'], function () {

            Route::resource('', 'SkroutzController');

        });
    });

});
