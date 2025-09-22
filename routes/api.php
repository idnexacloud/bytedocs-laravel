<?php

use Illuminate\Support\Facades\Route;

// Sample API routes for testing ByteDocs
Route::prefix('v1')->group(function () {
    // Users API
    Route::get('users', function () {
        return response()->json([
            'data' => [
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
                ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ]
        ]);
    })->name('users.index');

    Route::get('users/{id}', function ($id) {
        return response()->json([
            'data' => ['id' => $id, 'name' => 'John Doe', 'email' => 'john@example.com']
        ]);
    })->name('users.show');

    Route::post('users', function () {
        return response()->json([
            'data' => ['id' => 3, 'name' => 'New User', 'email' => 'new@example.com']
        ], 201);
    })->name('users.store');

    Route::put('users/{id}', function ($id) {
        return response()->json([
            'data' => ['id' => $id, 'name' => 'Updated User', 'email' => 'updated@example.com']
        ]);
    })->name('users.update');

    Route::delete('users/{id}', function ($id) {
        return response()->json(['message' => 'User deleted'], 204);
    })->name('users.destroy');

    // Products API
    Route::get('products', function () {
        return response()->json([
            'data' => [
                ['id' => 1, 'name' => 'Laptop', 'price' => 1000, 'category' => 'Electronics'],
                ['id' => 2, 'name' => 'Book', 'price' => 20, 'category' => 'Education'],
            ]
        ]);
    })->name('products.index');

    Route::get('products/{id}', function ($id) {
        return response()->json([
            'data' => ['id' => $id, 'name' => 'Laptop', 'price' => 1000, 'category' => 'Electronics']
        ]);
    })->name('products.show');

    // Categories API
    Route::get('categories', function () {
        return response()->json([
            'data' => [
                ['id' => 1, 'name' => 'Electronics'],
                ['id' => 2, 'name' => 'Education'],
            ]
        ]);
    })->name('categories.index');
});

// Auth API
Route::post('auth/login', function () {
    return response()->json([
        'token' => 'sample-jwt-token',
        'user' => ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com']
    ]);
})->name('auth.login');

Route::post('auth/register', function () {
    return response()->json([
        'token' => 'sample-jwt-token',
        'user' => ['id' => 2, 'name' => 'New User', 'email' => 'new@example.com']
    ], 201);
})->name('auth.register');