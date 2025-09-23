<?php

namespace ByteDocs\Laravel\Parser;

use Illuminate\Routing\Route as LaravelRoute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use ByteDocs\Laravel\Core\RouteInfo;
use ByteDocs\Laravel\Core\Parameter;

class LaravelParser
{
    protected array $handlerInfoCache = [];

    /**
     * Parse a Laravel route into RouteInfo(s)
     * Returns array of RouteInfo for routes with multiple methods
     */
    public function parseRoute(LaravelRoute $route): array
    {
        $methods = $route->methods();
        
        // Filter out HEAD method when GET is present
        if (in_array('GET', $methods) && in_array('HEAD', $methods)) {
            $methods = array_filter($methods, fn($method) => $method !== 'HEAD');
        }
        
        $path = '/' . trim($route->uri(), '/');
        
        // Get handler information
        $handlerInfo = $this->parseHandler($route);
        
        // Create separate RouteInfo for each method
        $routeInfos = [];
        foreach ($methods as $method) {
            $routeInfos[] = new RouteInfo(
                $method,
                $path,
                $route->getAction(),
                $route->middleware(),
                $handlerInfo['summary'] ?? '',
                $handlerInfo['description'] ?? '',
                $handlerInfo['parameters'] ?? []
            );
        }
        
        return $routeInfos;
    }

    /**
     * Parse handler method to extract documentation
     */
    protected function parseHandler(LaravelRoute $route): array
    {
        $action = $route->getAction();
        
        if (!isset($action['controller'])) {
            return $this->parseClosureHandler($route);
        }

        $controller = $action['controller'];
        
        // Handle array format: [ControllerClass::class, 'method']
        if (is_array($controller)) {
            if (count($controller) >= 2) {
                return $this->parseControllerHandler($controller[0] . '@' . $controller[1]);
            } elseif (count($controller) === 1) {
                return $this->parseControllerHandler($controller[0]);
            }
            return $this->parseClosureHandler($route);
        }

        return $this->parseControllerHandler($controller);
    }

    /**
     * Parse closure handler
     */
    protected function parseClosureHandler(LaravelRoute $route): array
    {
        // For closure routes, we can't easily extract documentation
        // Return default values based on route pattern
        return [
            'summary' => $this->generateSummaryFromRoute($route),
            'description' => '',
            'parameters' => [],
        ];
    }

    /**
     * Parse controller handler
     */
    protected function parseControllerHandler(string $controllerAction): array
    {
        // Handle different controller action formats
        if (str_contains($controllerAction, '@')) {
            // Old format: App\Http\Controllers\UserController@index
            $parts = explode('@', $controllerAction);
            if (count($parts) !== 2) {
                return $this->generateDefaultInfo('unknown');
            }
            [$controller, $method] = $parts;
        } else {
            // New format: App\Http\Controllers\UserController::class
            // Default to __invoke method for single callable
            $controller = $controllerAction;
            $method = '__invoke';
        }
        
        $cacheKey = $controller . '@' . $method;
        
        if (isset($this->handlerInfoCache[$cacheKey])) {
            return $this->handlerInfoCache[$cacheKey];
        }

        $handlerInfo = $this->extractControllerMethodInfo($controller, $method);
        $this->handlerInfoCache[$cacheKey] = $handlerInfo;
        
        return $handlerInfo;
    }

    /**
     * Extract information from controller method using reflection
     */
    protected function extractControllerMethodInfo(string $controller, string $method): array
    {
        try {
            $reflectionClass = new ReflectionClass($controller);
            $reflectionMethod = $reflectionClass->getMethod($method);
            
            $docComment = $reflectionMethod->getDocComment();
            
            if ($docComment) {
                return $this->parseDocComment($docComment);
            }
            
            return $this->generateDefaultInfo($method);
            
        } catch (ReflectionException $e) {
            return $this->generateDefaultInfo($method);
        }
    }

    /**
     * Parse PHPDoc comment to extract API documentation
     */
    protected function parseDocComment(string $docComment): array
    {
        $lines = explode("\n", $docComment);
        $summary = '';
        $description = '';
        $parameters = [];
        
        $summaryFound = false;
        
        foreach ($lines as $line) {
            $line = trim($line, " \t\n\r\0\x0B/*");
            
            if (empty($line)) {
                continue;
            }
            
            // Parse @param annotations
            if (preg_match('/^@param\s+(\w+)\s+(\w+)\s+(true|false)\s+"([^"]*)"/', $line, $matches)) {
                $parameters[] = new Parameter(
                    $matches[1],
                    $matches[2],
                    'string', // Default type
                    $matches[3] === 'true',
                    $matches[4]
                );
                continue;
            }
            
            // Parse other annotations
            if (str_starts_with($line, '@')) {
                continue;
            }
            
            // First non-annotation line is summary
            if (!$summaryFound && !empty($line)) {
                $summary = $line;
                $summaryFound = true;
                continue;
            }
            
            // Additional lines are description
            if ($summaryFound && !empty($line)) {
                $description .= ($description ? ' ' : '') . $line;
            }
        }
        
        return [
            'summary' => $summary,
            'description' => $description,
            'parameters' => $parameters,
        ];
    }

    /**
     * Generate default information for a method
     */
    protected function generateDefaultInfo(string $method): array
    {
        $action = $this->inferActionFromMethodName($method);
        
        return [
            'summary' => $action,
            'description' => $action . ' operation',
            'parameters' => [],
        ];
    }

    /**
     * Generate summary from route pattern
     */
    protected function generateSummaryFromRoute(LaravelRoute $route): string
    {
        $method = $route->methods()[0] ?? 'GET';
        $path = $route->uri();
        
        switch (strtoupper($method)) {
            case 'GET':
                $action = str_contains($path, '{') ? 'Get' : 'List';
                break;
            case 'POST':
                $action = 'Create';
                break;
            case 'PUT':
            case 'PATCH':
                $action = 'Update';
                break;
            case 'DELETE':
                $action = 'Delete';
                break;
            default:
                $action = $method;
                break;
        }
        
        // Extract resource name from path
        $parts = array_filter(explode('/', $path));
        $resource = 'resource';
        
        foreach (array_reverse($parts) as $part) {
            if (!str_contains($part, '{') && !in_array($part, ['api', 'v1', 'v2'])) {
                $resource = $part;
                break;
            }
        }
        
        return $action . ' ' . $resource;
    }

    /**
     * Infer action from method name
     */
    protected function inferActionFromMethodName(string $method): string
    {
        $method = strtolower($method);
        
        if (str_contains($method, 'index') || str_contains($method, 'list')) {
            return 'List items';
        }
        
        if (str_contains($method, 'show') || str_contains($method, 'get')) {
            return 'Get item';
        }
        
        if (str_contains($method, 'store') || str_contains($method, 'create')) {
            return 'Create item';
        }
        
        if (str_contains($method, 'update') || str_contains($method, 'put') || str_contains($method, 'patch')) {
            return 'Update item';
        }
        
        if (str_contains($method, 'destroy') || str_contains($method, 'delete')) {
            return 'Delete item';
        }
        
        return ucfirst($method);
    }
}