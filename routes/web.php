<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POIController;
use App\Http\Controllers\API\AndroidController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TestController;

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

Route::match(['get', 'post'], '/register', function () {
    return redirect('/');
});

//Route::get('/test', [POIController::class, 'getlongPOI']);
Route::get('/perm', [TestController::class, 'testFunction']);


//Route::get('/setup', [POIController::class, 'initialSetup']);

//logged in
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::prefix('poi')->group(function () {
        Route::get('', [POIController::class, 'index'])->name('index');
        Route::match(['get', 'post'],'/create', [POIController::class, 'create']);
        Route::get('/user', [POIController::class, 'userPOI']);
        Route::get('/show/{id?}', [POIController::class, 'show']);
        Route::match(['get', 'post'],'/rate/{id?}', [POIController::class, 'ratePOI']);
        Route::match(['get', 'post'],'/update/{id?}', [POIController::class, 'update']);
        Route::get('/delete_r/{poi?}/{user?}', [POIController::class, 'deleteRating']);
        Route::match(['get', 'post'],'/app/{long?}/{lat?}', [POIController::class, 'appRequest']);
        Route::get('/delete/{id?}', [POIController::class, 'destroy']);
        Route::get('/search', [POIController::class, 'categoryIndex']);
        Route::post('/search', [POIController::class, 'searchPOIs']);
    });

    Route::get('/dashboard', function () {
        return redirect()->route('index');
    });

    Route::get('', function () {
        return redirect()->route('index');
    });

    Route::get('/user/notify/users', [UserController::class, 'showNotify']);

    // wildcard documentation: https://spatie.be/docs/laravel-permission/v4/basic-usage/wildcard-permissions

    //administration
    Route::prefix('admin')->group(function () {

        //users
       // Route::group(['middleware' => ['can:admin.users.read']], function () {
            Route::prefix('user')->group(function () {
                Route::get('', [UserController::class, 'index'])->name('admin.user.index');
                Route::match(['get', 'post'],'/create', [UserController::class, 'createUser']);
                Route::match(['get', 'post'], '/edit/{id?}', [UserController::class, 'editUser']);
            });
       // });

        //permissions
        Route::group(['middleware' => ['can:admin.users.permissions.read']], function () {
            Route::prefix('perm')->group(function () {
                Route::match(['get', 'post'], '/create', [PermissionController::class, 'createPermission']);
                Route::match(['get', 'post'], '/delete', [PermissionController::class, 'deletePermission']);
            });
        });

        //roles
        Route::group(['middleware' => ['can:admin.users.roles.read']], function () {
            Route::prefix('role')->group(function () {
                Route::match(['get', 'post'], '/create', [PermissionController::class, 'createRole']);
                Route::match(['get', 'post'], '/delete', [PermissionController::class, 'deleteRole']);
                Route::match(['get', 'post'], '/edit/{role?}', [PermissionController::class, 'editRole']);
            });
        });

    });

});
