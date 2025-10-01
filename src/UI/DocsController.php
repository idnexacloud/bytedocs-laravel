<?php

namespace ByteDocs\Laravel\UI;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ByteDocs\Laravel\Core\APIDocs;
use ByteDocs\Laravel\AI\ChatRequest;

class DocsController extends Controller
{
    protected APIDocs $docs;

    public function __construct(APIDocs $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Show the main documentation page
     */
    public function index()
    {
        // Force route detection and generation
        $this->detectAndGenerateRoutes();
        
        $data = [
            'title' => $this->docs->getConfig()->title,
            'docsData' => $this->docs->getDocumentation()->toArray(),
            'config' => $this->docs->getConfig()->toArray(),
        ];

        return view('bytedocs::docs', $data);
    }

    /**
     * Detect routes and generate documentation
     */
    protected function detectAndGenerateRoutes(): void
    {
        $routes = \Route::getRoutes();
        $detectedCount = 0;
        $skippedRoutes = [];
        
        foreach ($routes as $route) {
            if ($this->shouldSkipRoute($route)) {
                $skippedRoutes[] = $route->uri() . ' (' . implode(', ', $route->methods()) . ')';
                continue;
            }
            
            $this->docs->addRoute($route);
            $detectedCount++;
        }
        
        
        // Generate documentation
        $this->docs->generate();
    }

    /**
     * Check if route should be skipped from documentation
     */
    protected function shouldSkipRoute($route): bool
    {
        $uri = $route->uri();
        $methods = $route->methods();
        $excludePaths = config('bytedocs.exclude_paths', []);

        // Skip HEAD and OPTIONS methods
        if (in_array('HEAD', $methods) && count($methods) === 1) {
            return true;
        }
        
        // Skip OPTIONS only routes
        if (in_array('OPTIONS', $methods) && count($methods) === 1) {
            return true;
        }

        // Skip ByteDocs routes
        $docsPath = trim(config('bytedocs.docs_path', '/docs'), '/');
        if (str_starts_with($uri, $docsPath)) {
            return true;
        }

        // Skip Laravel system routes
        $systemRoutes = ['_ignition', 'sanctum', 'telescope', 'horizon'];
        foreach ($systemRoutes as $systemRoute) {
            if (str_starts_with($uri, $systemRoute)) {
                return true;
            }
        }

        // Skip excluded paths from config
        foreach ($excludePaths as $excludePath) {
            $cleanExcludePath = trim($excludePath, '/');
            if (str_starts_with($uri, $cleanExcludePath)) {
                return true;
            }
        }

        // Skip Laravel internal routes
        if ($uri === '/' && empty($route->getName()) && in_array('GET', $methods)) {
            return true;
        }
        
        // Skip fallback routes
        if (str_contains($uri, '{fallbackPlaceholder}')) {
            return true;
        }

        return false;
    }

    /**
     * Return API documentation data as JSON
     */
    public function apiData(): JsonResponse
    {
        $this->docs->generate();
        
        return response()->json($this->docs->getDocumentation()->toArray())
            ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Return OpenAPI specification as JSON
     */
    public function openapi(): JsonResponse
    {
        // Force route detection and generation before exporting
        $this->detectAndGenerateRoutes();
        
        return response()->json($this->docs->getOpenAPIJSON())
            ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Return OpenAPI specification as YAML
     */
    public function openapiYaml()
    {
        // Force route detection and generation before exporting
        $this->detectAndGenerateRoutes();
        
        $yaml = $this->docs->getOpenAPIYAML();

        return response($yaml, 200)
            ->header('Content-Type', 'application/x-yaml')
            ->header('Content-Disposition', 'attachment; filename="openapi.yaml"')
            ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Handle AI chat requests
     */
    public function chat(Request $request): JsonResponse
    {
        // Enable CORS
        $response = response()->json([])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type');

        if ($request->getMethod() === 'OPTIONS') {
            return $response;
        }

        $validated = $request->validate([
            'message' => 'required|string',
            // Context and endpoint no longer needed from frontend
            // Backend will auto-provide complete API context
        ]);

        $chatRequest = new ChatRequest(
            $validated['message']
            // Context will be auto-provided by backend via getAPIContext()
        );

        $result = $this->docs->handleChat($chatRequest);

        return response()->json($result)
            ->header('Access-Control-Allow-Origin', '*');
    }
}