<?php

namespace ByteDocs\Laravel\UI;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use ByteDocs\Laravel\Core\APIDocs;
use ByteDocs\Laravel\AI\ChatRequest;
use ByteDocs\Laravel\Performance\K6Runner;

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

    /**
     * Run k6 performance test
     */
    public function runPerformanceTest(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'url' => 'required|string',
                'method' => 'required|string|in:GET,POST,PUT,PATCH,DELETE',
                'mode' => 'required|string|in:constant,stages',
                'headers' => 'nullable|array',
                'body' => 'nullable|array',
                'vus' => 'nullable|integer|min:1|max:1000',
                'duration' => 'nullable|string',
                'iterations' => 'nullable|integer|min:1',
                'stages' => 'nullable|array',
                'stages.*.duration' => 'required_with:stages|string',
                'stages.*.target' => 'required_with:stages|integer|min:0',
                'think_time' => 'nullable|numeric|min:0',
                'k6_path' => 'nullable|string',
            ]);

            $customK6Path = $validated['k6_path'] ?? null;
            $k6Runner = new K6Runner($customK6Path);
            $results = $k6Runner->runTest($validated);

            return response()->json([
                'success' => true,
                'results' => $results,
            ])->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500)->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Generate k6 script without running
     */
    public function generateK6Script(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'url' => 'required|string',
                'method' => 'required|string|in:GET,POST,PUT,PATCH,DELETE',
                'mode' => 'required|string|in:constant,stages',
                'headers' => 'nullable|array',
                'body' => 'nullable|array',
                'vus' => 'nullable|integer|min:1|max:1000',
                'duration' => 'nullable|string',
                'iterations' => 'nullable|integer|min:1',
                'stages' => 'nullable|array',
                'stages.*.duration' => 'required_with:stages|string',
                'stages.*.target' => 'required_with:stages|integer|min:0',
                'think_time' => 'nullable|numeric|min:0',
                'k6_path' => 'nullable|string',
            ]);

            $customK6Path = $validated['k6_path'] ?? null;
            $k6Runner = new K6Runner($customK6Path);
            $script = $k6Runner->generateScriptContent($validated);

            return response()->json([
                'success' => true,
                'script' => $script,
            ])->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500)->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Get k6 system information
     */
    public function getK6SystemInfo(): JsonResponse
    {
        try {
            $k6Runner = new K6Runner();
            $systemInfo = $k6Runner->getSystemInfo();

            return response()->json([
                'success' => true,
                'system_info' => $systemInfo,
            ])->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'installed' => false,
            ])->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Analyze performance test results with AI
     */
    public function analyzePerformanceWithAI(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'results' => 'required|array',
                'language' => 'nullable|string|in:en,id,zh,ja,ko,es,fr,de,pt,ru',
            ]);

            $results = $validated['results'];
            $language = $validated['language'] ?? 'en';

            // Build AI prompt for performance analysis
            $prompt = $this->buildPerformanceAnalysisPrompt($results, $language);

            // Use the same AI client as chat
            $chatRequest = new ChatRequest($prompt);
            $analysis = $this->docs->handleChat($chatRequest);

            return response()->json([
                'success' => true,
                'analysis' => $analysis['response'] ?? $analysis['message'] ?? 'No analysis available',
            ])->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500)->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Build AI prompt for performance analysis
     */
    protected function buildPerformanceAnalysisPrompt(array $results, string $language = 'en'): string
    {
        $summary = $results['summary'] ?? [];
        $output = $results['output'] ?? '';

        // Language map for instructions
        $languageNames = [
            'en' => 'English',
            'id' => 'Bahasa Indonesia',
            'zh' => 'Chinese (中文)',
            'ja' => 'Japanese (日本語)',
            'ko' => 'Korean (한국어)',
            'es' => 'Spanish (Español)',
            'fr' => 'French (Français)',
            'de' => 'German (Deutsch)',
            'pt' => 'Portuguese (Português)',
            'ru' => 'Russian (Русский)',
        ];

        $languageName = $languageNames[$language] ?? 'English';

        $prompt = "You are a performance testing expert. Analyze the following k6 load test results and provide actionable insights.\n\n";

        // Add language instruction
        if ($language !== 'en') {
            $prompt .= "IMPORTANT: Respond ENTIRELY in {$languageName}. All text, headings, analysis, and recommendations must be in {$languageName}.\n\n";
        }

        $prompt .= "## Test Results Summary\n\n";

        if (!empty($summary['total_requests'])) {
            $prompt .= "- Total Requests: " . $summary['total_requests'] . "\n";
        }

        if (!empty($summary['avg_response_time'])) {
            $prompt .= "- Average Response Time: " . $summary['avg_response_time'] . "\n";
        }

        if (!empty($summary['failure_rate'])) {
            $prompt .= "- Failure Rate: " . $summary['failure_rate'] . "\n";
        }

        if (!empty($summary['iterations'])) {
            $prompt .= "- Iterations: " . $summary['iterations'] . "\n";
        }

        if (!empty($summary['max_vus'])) {
            $prompt .= "- Max Virtual Users: " . $summary['max_vus'] . "\n";
        }

        if (!empty($output)) {
            $prompt .= "\n## Full k6 Output\n\n```\n" . substr($output, 0, 2000) . "\n```\n\n";
        }

        $prompt .= "\nPlease provide:\n";
        $prompt .= "1. **Overall Performance Assessment**: Rate the performance (Excellent/Good/Fair/Poor)\n";
        $prompt .= "2. **Key Findings**: What are the main performance characteristics?\n";
        $prompt .= "3. **Potential Issues**: Identify any performance bottlenecks or concerns\n";
        $prompt .= "4. **Optimization Recommendations**: Specific, actionable suggestions to improve performance\n";
        $prompt .= "5. **Scalability Assessment**: Can this endpoint handle production load?\n\n";
        $prompt .= "Keep the analysis concise, technical, and actionable. Focus on practical improvements.";

        if ($language !== 'en') {
            $prompt .= "\n\nRemember: Your entire response must be in {$languageName}.";
        }

        return $prompt;
    }
}