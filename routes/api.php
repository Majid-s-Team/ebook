<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PasswordController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\MediaUploadController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\BookCategoryController;
use App\Http\Controllers\API\ReelController;
use App\Http\Controllers\API\ReelInteractionController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\{
    CartController,
    OrderController,
    PromoCodeController,
    DeliveryAddressController,
    PaymentCardController,
    NotificationController
};

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

Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('verify-otp', [PasswordController::class, 'verifyOtp']);
Route::post('reset-password', [PasswordController::class, 'resetPassword']);
Route::post('/upload-media', [MediaUploadController::class, 'upload'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [PasswordController::class, 'changePassword']);
    Route::get('profile', [ProfileController::class, 'view']);
    Route::post('profile-update', [ProfileController::class, 'update']);


    Route::post('/reels', [ReelController::class, 'store']);
    Route::get('/reels', [ReelController::class, 'index']);
    Route::delete('/reels/{id}', [ReelController::class, 'destroy']);

    Route::post('/reels/{id}/like-toggle', [ReelInteractionController::class, 'toggleLike']);

    Route::get('/reels/{id}/comments', [ReelInteractionController::class, 'listComments']);
    Route::post('/reels/{id}/comments', [ReelInteractionController::class, 'storeComment']);
    Route::put('/reel-comments/{id}', [ReelInteractionController::class, 'updateComment']);
    Route::delete('/reel-comments/{id}', [ReelInteractionController::class, 'deleteComment']);

    Route::middleware('auth:sanctum')->prefix('book-categories')->group(function () {

        Route::get('/', [BookCategoryController::class, 'index']);
        Route::get('/favorites', [BookCategoryController::class, 'favoriteCategories']);
        Route::post('/', [BookCategoryController::class, 'store']);
        Route::get('/popular', [BookCategoryController::class, 'popular']);
        Route::get('/{id}', [BookCategoryController::class, 'show']);
        Route::put('/{id}', [BookCategoryController::class, 'update']);
        Route::delete('/{id}', [BookCategoryController::class, 'destroy']);
        Route::patch('/{id}/status', [BookCategoryController::class, 'toggleStatus']);

        Route::post('/{id}/favorite', [BookCategoryController::class, 'toggleFavorite']);

    });

    Route::prefix('books')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::post('/', [BookController::class, 'store']);
        Route::get('/popular', [BookController::class, 'popular']);
        Route::get('/favorites', [BookController::class, 'favoriteBooks']);
        Route::get('/{id}', [BookController::class, 'show']);
        Route::put('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'destroy']);

        Route::post('/{id}/toggle-status', [BookController::class, 'toggleStatus']);
        Route::post('/{id}/toggle-favorite', [BookController::class, 'toggleFavorite']);
    });

    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'addItem']);
    Route::post('cart/remove', [CartController::class, 'removeItem']);
    Route::post('cart/update-quantity', [CartController::class, 'updateItemQuantity']);
    Route::post('cart/apply-promo', [CartController::class, 'applyPromo']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{id}', [OrderController::class, 'show']);

    Route::get('promo-codes/validate/{code}', [PromoCodeController::class, 'validateCode']);


    Route::apiResource('delivery-addresses', DeliveryAddressController::class);

    Route::apiResource('payment-cards', PaymentCardController::class);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
     Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('clear/{id}', [NotificationController::class, 'destroy']);
    Route::delete('/clearAll', [NotificationController::class, 'clearAll']);


});





// Route::get('roles', [RoleController::class, 'index']);
// Route::post('roles', [RoleController::class, 'store']);
// Route::delete('roles/{id}', [RoleController::class, 'destroy']);

// // Permission management
// Route::get('permissions', [PermissionController::class, 'index']);
// Route::post('permissions', [PermissionController::class, 'store']);
// Route::delete('permissions/{id}', [PermissionController::class, 'destroy']);

// // Assign role/permission to user
// Route::post('assign-role/{userId}', [PermissionController::class, 'assignRole']);
// Route::post('assign-permission/{userId}', [PermissionController::class, 'assignPermission']);
// Route::post('assign-multiple-permissions/{userId}', [PermissionController::class, 'assignMultiplePermissions']);


