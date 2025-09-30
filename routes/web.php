<?php

use Illuminate\Support\Facades\Route;
use ByteDocs\Laravel\UI\DocsController;

$docsPath = config('bytedocs.docs_path', '/docs');
$docsPath = trim($docsPath, '/');

Route::middleware(['web', 'bytedocs.auth'])->group(function () use ($docsPath) {
    Route::match(['GET', 'POST'], $docsPath, [DocsController::class, 'index'])->name('bytedocs.index');
    Route::get($docsPath . '/api-data.json', [DocsController::class, 'apiData'])->name('bytedocs.api-data');
    Route::get($docsPath . '/openapi.json', [DocsController::class, 'openapi'])->name('bytedocs.openapi');
    Route::get($docsPath . '/openapi.yaml', [DocsController::class, 'openapiYaml'])->name('bytedocs.openapi.yaml');
    Route::post($docsPath . '/chat', [DocsController::class, 'chat'])->name('bytedocs.chat');
    Route::match(['GET', 'POST'], $docsPath . '/{path?}', [DocsController::class, 'index'])
        ->where('path', '.*')
        ->name('bytedocs.spa');
});