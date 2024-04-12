<?php

use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\NewPostController;
use App\Http\Controllers\LoginRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => ['cors']], function () {

Route::controller(LoginRegisterController::class)->group(function () {
    Route::post('/auth/register', 'store');
    Route::post('/auth/login', 'authenticate');
    // });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', 'logout')->name('logout');
        Route::resource('post', PostController::class);

        Route::get('post/like/{post?}', [PostController::class, 'postLike']);
        Route::Post('post/comment/{post?}', [PostController::class, 'comments']);
    });
});
// });
