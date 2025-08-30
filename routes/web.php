<?php

use Illuminate\Support\Facades\Route;
use ByteDocs\Laravel\UI\DocsController;

// Get docs path from config
$docsPath = config('bytedocs.docs_path', '/docs');
$docsPath = trim($docsPath, '/');

// Documentation routes with authentication middleware
Route::middleware(['web', 'bytedocs.auth'])->group(function () use ($docsPath) {
    // Main documentation route - accepts both GET and POST for authentication
    Route::match(['GET', 'POST'], $docsPath, [DocsController::class, 'index'])->name('bytedocs.index');

    // API data endpoint  
    Route::get($docsPath . '/api-data.json', [DocsController::class, 'apiData'])->name('bytedocs.api-data');

    // OpenAPI JSON endpoint
    Route::get($docsPath . '/openapi.json', [DocsController::class, 'openapi'])->name('bytedocs.openapi');

    // AI Chat endpoint
    Route::post($docsPath . '/chat', [DocsController::class, 'chat'])->name('bytedocs.chat');

    // Handle all sub-routes for client-side routing (React Router) - accepts both GET and POST
    Route::match(['GET', 'POST'], $docsPath . '/{path?}', [DocsController::class, 'index'])
        ->where('path', '.*')
        ->name('bytedocs.spa');
});