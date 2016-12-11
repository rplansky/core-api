<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
        'namespace' => 'Api',
    ],
    function () {
        Route::group(['prefix' => 'v1', 'namespace' => 'V1'], function () {
            Route::any('/', 'IndexController@root');
            Route::get('/documentation', 'IndexController@documentation');

            Route::get('/product/{id}', 'ProductsController@show');
        });

        Route::any('/', 'IndexController@root');
    }
);
