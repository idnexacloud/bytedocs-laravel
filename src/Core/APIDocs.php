<?php

namespace ByteDocs\Laravel\Core;

use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response as LaravelResponse;
use ByteDocs\Laravel\AI\Client as AIClient;
use ByteDocs\Laravel\AI\ChatRequest;
use ByteDocs\Laravel\Parser\LaravelParser;

class APIDocs
{
    protected Config $config;
    protected Documentation $documentation;
    protected array $routes = [];
    protected array $schemas = [];
    protected ?AIClient $llmClient = null;
    protected LaravelParser $parser;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->parser = new LaravelParser();
        
        $this->documentation = new Documentation(
            new APIInfo(
                $config->title,
                $config->version,
                $config->description,
                $config->baseURL
            )
        );

        // Initialize LLM client if AI is enabled
        if ($config->aiConfig && $config->aiConfig->enabled) {
            $this->llmClient = AIClient::create($config->aiConfig);
        }
    }

    /**
     * Add Laravel route to documentation
     */
    public function addRoute(LaravelRoute $route): void
    {
        $routeInfos = $this->parser->parseRoute($route);
        foreach ($routeInfos as $routeInfo) {
            $this->routes[] = $routeInfo;
        }
    }

    /**
     * Manually add route information
     */
    public function addRouteInfo(RouteInfo $routeInfo): void
    {
        $this->routes[] = $routeInfo;
    }

    /**
     * Generate the API documentation
     */
    public function generate(): void
    {
        $sections = [];

        foreach ($this->routes as $route) {
            $endpoint = $this->processRoute($route);
            $sectionName = $this->extractSection($route->path);

            if (!isset($sections[$sectionName])) {
                $sections[$sectionName] = new EndpointSection(
                    $sectionName,
                    $this->formatSectionName($sectionName),
                    $this->formatSectionName($sectionName) . ' related endpoints'
                );
            }

            $sections[$sectionName]->endpoints[] = $endpoint;
        }

        $this->documentation->endpoints = array_values($sections);
    }

    /**
     * Process a single route into an endpoint
     */
    protected function processRoute(RouteInfo $route): Endpoint
    {
        $summary = $route->summary ?: $this->generateSummary($route->method, $route->path);
        $description = $route->description ?: $summary;

        $pathParams = $this->extractPathParameters($route->path);
        $allParams = $this->mergeParameters($pathParams, $route->parameters);

        return new Endpoint(
            id: $this->generateID($route->method, $route->path),
            method: $route->method,
            path: $route->path,
            summary: $summary,
            description: $description,
            parameters: $allParams,
            requestBody: $this->extractRequestBody($route->handler),
            responses: $this->generateResponses($route->handler)
        );
    }

    /**
     * Extract path parameters from route path
     */
    protected function extractPathParameters(string $path): array
    {
        $params = [];
        
        // Match Laravel route parameters like {id}, {user}, etc.
        preg_match_all('/\{([^}]+)\}/', $path, $matches);
        
        foreach ($matches[1] as $param) {
            // Remove optional marker if present
            $paramName = str_replace('?', '', $param);
            $required = !str_contains($param, '?');
            
            $params[] = new Parameter(
                name: $paramName,
                in: 'path',
                type: 'string',
                required: $required,
                description: ucfirst($paramName) . ' parameter'
            );
        }

        return $params;
    }

    /**
     * Merge path parameters with provided parameters
     */
    protected function mergeParameters(array $pathParams, array $providedParams): array
    {
        $paramMap = [];

        // Add path parameters first
        foreach ($pathParams as $param) {
            $key = $param->name . ':' . $param->in;
            $paramMap[$key] = $param;
        }

        // Add provided parameters, overriding if same name+location
        foreach ($providedParams as $param) {
            $key = $param->name . ':' . $param->in;
            $paramMap[$key] = $param;
        }

        return array_values($paramMap);
    }

    /**
     * Extract request body from handler (simplified for now)
     */
    protected function extractRequestBody(mixed $handler): ?RequestBody
    {
        // For now, return null - can be enhanced with reflection
        // to analyze the handler method parameters
        return null;
    }

    /**
     * Generate response examples
     */
    protected function generateResponses(mixed $handler): array
    {
        return [
            '200' => new Response('Success', ['status' => 'success']),
            '400' => new Response('Bad Request'),
            '404' => new Response('Not Found'),
            '500' => new Response('Internal Server Error'),
        ];
    }

    /**
     * Extract section name from path
     */
    protected function extractSection(string $path): string
    {
        $parts = array_filter(explode('/', trim($path, '/')));

        // For API paths like /api/v1/users, extract the resource name (users)
        foreach (array_reverse($parts) as $part) {
            if (!empty($part) && !str_starts_with($part, '{') && !str_contains($part, '{')) {
                // Skip version numbers and api prefixes
                if ($part !== 'api' && !preg_match('/^v\d+$/', $part)) {
                    return $part;
                }
            }
        }

        // Fallback to first non-empty part
        if (!empty($parts)) {
            return $parts[0];
        }

        return 'default';
    }

    /**
     * Format section name for display
     */
    protected function formatSectionName(string $section): string
    {
        return Str::title($section);
    }

    /**
     * Generate endpoint ID
     */
    protected function generateID(string $method, string $path): string
    {
        return strtolower($method) . '-' . str_replace(['/', '{', '}'], ['-', '', ''], $path);
    }

    /**
     * Generate summary from method and path
     */
    protected function generateSummary(string $method, string $path): string
    {
        $section = $this->extractSection($path);
        $action = $this->inferAction($method, $path);
        return $action . ' ' . $section;
    }

    /**
     * Infer action from HTTP method and path
     */
    protected function inferAction(string $method, string $path): string
    {
        return match (strtoupper($method)) {
            'GET' => str_contains($path, '{') ? 'Get' : 'List',
            'POST' => 'Create',
            'PUT', 'PATCH' => 'Update',
            'DELETE' => 'Delete',
            default => $method,
        };
    }

    /**
     * Get the generated documentation
     */
    public function getDocumentation(): Documentation
    {
        return $this->documentation;
    }

    /**
     * Get configuration
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Get OpenAPI JSON format
     */
    public function getOpenAPIJSON(): array
    {
        $this->generate();

        $openAPI = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => $this->documentation->info->title,
                'version' => $this->documentation->info->version,
                'description' => $this->documentation->info->description,
            ],
            'servers' => [],
            'paths' => [],
            'components' => [
                'schemas' => $this->documentation->schemas,
            ],
        ];

        // Add servers
        if (!empty($this->config->baseURLs)) {
            foreach ($this->config->baseURLs as $baseURL) {
                $openAPI['servers'][] = [
                    'url' => $baseURL['url'],
                    'description' => $baseURL['name'],
                ];
            }
        } elseif ($this->config->baseURL) {
            $openAPI['servers'][] = ['url' => $this->config->baseURL];
        }

        // Convert endpoints to paths
        $paths = [];
        foreach ($this->documentation->endpoints as $section) {
            foreach ($section->endpoints as $endpoint) {
                $pathKey = $endpoint->path;
                if (!isset($paths[$pathKey])) {
                    $paths[$pathKey] = [];
                }

                $methodKey = strtolower($endpoint->method);
                $operation = [
                    'summary' => $endpoint->summary,
                    'description' => $endpoint->description,
                    'tags' => [$section->name],
                    'operationId' => $endpoint->id,
                    'parameters' => [],
                    'responses' => [],
                ];

                // Add parameters
                if (!empty($endpoint->parameters)) {
                    foreach ($endpoint->parameters as $param) {
                        $operation['parameters'][] = [
                            'name' => $param->name,
                            'in' => $param->in,
                            'required' => $param->required,
                            'description' => $param->description,
                            'schema' => ['type' => $param->type],
                            'example' => $param->example,
                        ];
                    }
                }

                // Add request body
                if ($endpoint->requestBody) {
                    $operation['requestBody'] = [
                        'required' => $endpoint->requestBody->required,
                        'content' => [
                            $endpoint->requestBody->contentType => [
                                'schema' => $endpoint->requestBody->schema,
                                'example' => $endpoint->requestBody->example,
                            ],
                        ],
                    ];
                }

                // Add responses
                foreach ($endpoint->responses as $statusCode => $response) {
                    $operation['responses'][$statusCode] = [
                        'description' => $response->description,
                        'content' => [
                            'application/json' => [
                                'schema' => $response->schema,
                                'example' => $response->example,
                            ],
                        ],
                    ];
                }

                $paths[$pathKey][$methodKey] = $operation;
            }
        }

        $openAPI['paths'] = $paths;
        return $openAPI;
    }

    /**
     * Get API context for LLM
     */
    public function getAPIContext(): string
    {
        $openAPIJSON = $this->getOpenAPIJSON();
        $jsonString = json_encode($openAPIJSON, JSON_PRETTY_PRINT);

        return sprintf(
            "=== API SPECIFICATION FOR YOUR REFERENCE ===

API Title: %s
Version: %s  
Description: %s
Base URLs: %s

=== COMPLETE OPENAPI JSON SPECIFICATION ===
%s

=== STRICT INSTRUCTIONS ===
- ONLY answer programming or API-related questions about the OpenAPI JSON specification above.
- DO NOT answer questions outside the context of this API or its OpenAPI spec.
- DO NOT provide information unrelated to the API, its endpoints, or usage.
- ONLY use the provided OpenAPI JSON as your source of truth.
- Give code examples, endpoint usage, and parameter details strictly based on the OpenAPI spec.
- Be precise about required/optional parameters and show real request/response JSON from the spec.
- DO NOT speculate or invent endpoints, parameters, or behaviors not present in the OpenAPI JSON.",
            $this->documentation->info->title,
            $this->documentation->info->version,
            $this->documentation->info->description,
            json_encode($this->config->baseURLs),
            $jsonString
        );
    }

    /**
     * Handle chat requests
     */
    public function handleChat(ChatRequest $request): array
    {
        if (!$this->llmClient) {
            return [
                'error' => 'AI chat is not enabled or configured',
                'provider' => 'none',
            ];
        }

        if (empty($request->message)) {
            return [
                'error' => 'Message is required',
                'provider' => $this->llmClient->getProvider(),
            ];
        }

        // Automatically include API context if not provided
        if (empty($request->context)) {
            $request->context = $this->getAPIContext();
        }

        try {
            return $this->llmClient->chat($request);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'provider' => $this->llmClient->getProvider(),
            ];
        }
    }
}