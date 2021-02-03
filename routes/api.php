<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
/*
//Con el tercer paramero de only, me permite definir los metodos httpd a mostrar.
//como estoy haciendo un curso dejare todos los metodos a motrar 
Route::resource('buyers', 'Buyer\BuyerController', ['only' =>['index', 'show']]);
*/

/*
* Buyers
*/

Route::resource('users', 'User\UserController');


Route::resource('buyers', 'Buyer\BuyerController');

/*
* Categories
*/
Route::resource('Categories', 'Category\CategoryController');

/*
* Products
*/
Route::resource('Products', 'Product\ProductController');

/*
* transaction
*/
Route::resource('transaction', 'Transaction\TransactionController');

/*
* Sellers
*/
Route::resource('Sellers', 'Seller\SellerController');

/*
* Users
*/
Route::resource('Users', 'User\UserController');