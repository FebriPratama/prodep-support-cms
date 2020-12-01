<?php

use Dingo\Api\Routing\Router;
use Illuminate\Http\Request;

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

/*
 * Welcome route - link to any public API documentation here
*/

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => ['api']], function (Router $api) {

    $api->group(['prefix' => 'api'], function (Router $api) {

        $api->group(['prefix' => 'v1'], function (Router $api) {

            $api->group(['prefix' => 'faq'], function (Router $api) {
                $api->get('/', 'App\Http\Controllers\Api\FaqController@index');
                $api->get('{id}', 'App\Http\Controllers\Api\FaqController@show');
            });

            /*
             * Authentication
             */
            $api->group(['prefix' => 'auth'], function (Router $api) {
                $api->group(['prefix' => 'jwt'], function (Router $api) {
                    $api->get('/token', 'App\Http\Controllers\Auth\AuthController@token');
                    $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
                    $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
                });
            });

            /*
             * Authenticated routes
             */
            $api->group(['prefix' => 'member', 'middleware' => ['api.auth']], function (Router $api) {

                /*
                 * Authentication
                 */
                $api->group(['prefix' => 'jwt'], function (Router $api) {
                    $api->get('/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
                    $api->delete('/token', 'App\Http\Controllers\Auth\AuthController@logout');
                });

                $api->get('/user', 'App\Http\Controllers\Auth\AuthController@getUser');

                /*
                 * Sales Order
                 */
                $api->group(['prefix' => 'salesorder'], function (Router $api) {
                    $api->get('/', 'App\Http\Controllers\Api\SalesOrderController@index');
                    $api->get('/{id}', 'App\Http\Controllers\Api\SalesOrderController@show');
                });

                /*
                 * Problem
                 */
                $api->group(['prefix' => 'problem'], function (Router $api) {
                    $api->get('/', 'App\Http\Controllers\Api\ProblemController@index');
                    $api->get('/{id}', 'App\Http\Controllers\Api\ProblemController@show');
                    $api->get('/{id}/list', 'App\Http\Controllers\Api\ProblemController@showList');
                });

                /*
                 * Complain
                 */
                $api->group(['prefix' => 'complain'], function (Router $api) {
                    $api->get('/', 'App\Http\Controllers\Api\ComplainFormController@index');
                    $api->get('/{id}', 'App\Http\Controllers\Api\ComplainFormController@show');
                    $api->post('/', 'App\Http\Controllers\Api\ComplainFormController@store');
                    $api->post('/{id}/changestatus', 'App\Http\Controllers\Api\ComplainFormController@changeStatus');
                });

                /*
                 * Instagram
                 */
                $api->group(['prefix' => 'instagram'], function (Router $api) {
                    $api->get('/', 'App\Http\Controllers\Api\InstagramController@index');
                    $api->get('/{id}', 'App\Http\Controllers\Api\InstagramController@singleMedia');
                    $api->get('/{id}/comments', 'App\Http\Controllers\Api\InstagramController@mediaComment');
                    $api->get('/{id}/replies', 'App\Http\Controllers\Api\InstagramController@getReplies');
                    $api->post('/{id}/replies', 'App\Http\Controllers\Api\InstagramController@storeReply');
                });

                /*
                 * Message
                 */
                $api->group(['prefix' => 'messages'], function (Router $api) {
                    $api->get('/{id}', 'App\Http\Controllers\Api\MessageController@index');
                    $api->post('/{id}', 'App\Http\Controllers\Api\MessageController@store');
                });

                /*
                 * Thread
                 */
                $api->group(['prefix' => 'thread'], function (Router $api) {
                    $api->get('/', 'App\Http\Controllers\Api\ThreadController@index');
                });
                
                
            });


        });

    });

});
