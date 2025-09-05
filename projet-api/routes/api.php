<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Register2Controller;

// ------------------
// Auth
// ------------------
Route::post('/register', [Register2Controller::class, 'register'])->name('register');
Route::post('/verification', [Register2Controller::class, 'verification'])->name('verification');
Route::post('/password-email', [ForgotPasswordController::class, 'email'])->name('password.email');
Route::post('/password-reset', [ForgotPasswordController::class, 'reset'])->name('password.reset');

// ------------------
// Données publiques
// ------------------
Route::get('/cities', fn() => \App\Models\City::all());

/********** Publics ************/

// Produits
Route::get('/products', [ProductController::class, 'index']); // liste tous les produits
Route::get('/products/{product}', [ProductController::class, 'show']); // détail d'un produit

// Catégories
Route::get('/categories', [CategoryController::class, 'index']); // liste des catégories
Route::get('/categories/{category}', [CategoryController::class, 'show']); // détails d'une catégorie et ses produits

// Options
Route::get('/options', [OptionController::class, 'index']); // liste des options
Route::get('/options/{option}', [OptionController::class, 'show']); // détail d'une option

// Messages
Route::post('/messages', [MessageController::class, 'store']);

// ------------------
// Utilisateurs connectés
// ------------------
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    Route::get('/user', fn(Request $request) => $request->user());
    Route::put('/users/address', [UserController::class, 'updateAddress']);

    // Product-User (panier)
    Route::get('/product_users', [ProductUserController::class, 'index']);
    Route::post('/product_users', [ProductUserController::class, 'store']);
    Route::put('/product_users/{product}', [ProductUserController::class, 'update']);
    Route::delete('/product_users/{product}', [ProductUserController::class, 'destroy']);
    Route::delete('/product_users', [ProductUserController::class, 'clearCart']);


    // Catégories
    Route::get('/categories/search', [CategoryController::class, 'search']);

    // Options
    Route::get('/options/search', [OptionController::class, 'search']);
});

// ------------------
// Admin uniquement
// ------------------
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Produits admin
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Catégories admin
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Options admin
    Route::post('/options', [OptionController::class, 'store']);
    Route::put('/options/{option}', [OptionController::class, 'update']);
    Route::delete('/options/{option}', [OptionController::class, 'destroy']);

    // CRUD Users admin
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Afficher les messages reçus
    Route::get('/messages', [MessageController::class, 'index']); 

});

