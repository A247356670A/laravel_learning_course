<?php

use App\Events\ChatMessage;
// use App\Http\Controllers\TestController;
// ---------------Monday------------------
use App\Http\Controllers\MondayAuthController;
use App\Http\Controllers\boardController;
// ---------------In App------------------
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// ------------------------------------Monday---------------------------------------
Route::get('/', function () {
    return view('welcome');
});

# receive token
Route::get('/monday/auth', [MondayAuthController::class, 'redirectToMonday']);
Route::get('/oauth/callback', [MondayAuthController::class, 'handleMondayCallback']);

# boards
Route::get('/retrieve-board/{id}', [boardController::class, 'retreiveBoards']);
Route::post('/create-board', [boardController::class, 'createBoard']);
Route::post('/duplicate-board', [boardController::class, 'duplicateBoard']);
Route::post('/update-board', [boardController::class, 'updateBoard']);
Route::post('/delete-board', [boardController::class, 'deleteBoard']);



// ------------------------------------In App---------------------------------------
// Gate
// Route::get('/admin', function() {
//     if(Gate::allows('visitAdminPages')){
//         return "Admin pass";
//     }
//     return "Admin only";
// });
Route::get('/admin', function () {
    return "Admin only";
})->middleware("can:visitAdminPages");
// User routes
Route::get('/', [UserController::class, "showCorrectHomePage"])->name("login");

// Route::get('/about', [TestController::class, "about"]);
// Route::get('/welcome', [TestController::class, "welcome"]);

Route::post('/register', [UserController::class, "register"])->middleware("guest");

Route::post('/login', [UserController::class, "login"])->middleware("guest");
Route::post('/logout', [UserController::class, "logout"])->middleware("mustBeLoggedIn");
Route::get("/manage-avatar", [UserController::class, "showAvatarForm"])->middleware("mustBeLoggedIn");
Route::post("/manage-avatar", [UserController::class, "storeAvatarForm"])->middleware("mustBeLoggedIn");


// Blog routes
Route::get('/create-post', [PostController::class, "showCreateForm"])->middleware("mustBeLoggedIn");
Route::post('/create-post', [PostController::class, "saveCreateForm"])->middleware("mustBeLoggedIn");

Route::get('/post/{page}', [PostController::class, "viewPost"]);

Route::get('/post/{page}/edit', [PostController::class, "editPostForm"])->middleware("can:update,page");
Route::put('/post/{page}', [PostController::class, "updatePost"])->middleware("can:update,page");

Route::delete("/post/{page}", [PostController::class, "delete"])->middleware('can:delete,page');

Route::get('/search/{term}', [PostController::class, "search"]);
// Profile routes

Route::get('/profile/{user:username}', [UserController::class, "profile"]);
Route::get('/profile/{user:username}/followers', [UserController::class, "profileFollowers"]);
Route::get('/profile/{user:username}/following', [UserController::class, "profileFollowing"]);

Route::middleware("cache.headers:public;max_age=20;etag")->group(function () {
    Route::get('/profile/{user:username}/raw', [UserController::class, "profileRaw"]);
    Route::get('/profile/{user:username}/followers/raw', [UserController::class, "profileFollowersRaw"]);
    Route::get('/profile/{user:username}/following/raw', [UserController::class, "profileFollowingRaw"]);
});



// Route::get('/about', function () {
//     return null;
// });

//Follow Related Routes
Route::post('/create-follow/{user:username}', [FollowController::class, "createFollow"])->middleware("mustBeLoggedIn");
Route::post('/remove-follow/{user:username}', [FollowController::class, "removeFollow"])->middleware("mustBeLoggedIn");

// Chat route
Route::post('/send-chat-message', function (Request $request) {
    Log::debug($request);
    $formFields = $request->validate([
        'textvalue' => 'required',
    ]);
    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
    }
    /** @var \App\Models\User $user **/
    $user = auth()->user();
    Log::debug($user);
    broadcast(new ChatMessage(['username' => $user->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => $user->avatar]))->toOthers();
    Log::debug('reach broadcast');
    return response()->noContent();
})->middleware('mustBeLoggedIn');
