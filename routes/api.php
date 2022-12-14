<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ArticleController;

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

Route::middleware(['require.user.api'])->group(function () {
    Route::resource('articles', ArticleController::class, [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);
    Route::post('votes/{articleId}', [ArticleController::class, 'votes']);
});


Route::resource('users', UserController::class, [
    'only' => ['index', 'store']
]);

Route::post('login', [AuthController::class, 'login']);
Route::get('all-articles', [ArticleController::class, 'allArticles']);
Route::get('top-categories', [ArticleController::class, 'getTopCategories']);
