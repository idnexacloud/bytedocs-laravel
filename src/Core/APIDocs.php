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

        $requestBody = $this->extractRequestBody($route->handler);

        return new Endpoint(
            $this->generateID($route->method, $route->path),
            $route->method,
            $route->path,
            $summary,
            $description,
            $allParams,
            $requestBody,
            $this->generateResponses($route->handler, $route)
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
                $paramName,
                'path',
                'string',
                $required,
                ucfirst($paramName) . ' parameter'
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
     * Extract request body from handler
     */
    protected function extractRequestBody($handler)
    {
        // Handle controller methods
        if (isset($handler['controller'])) {
            return $this->extractControllerRequestBody($handler['controller']);
        }
        
        // Handle closure routes
        if (isset($handler['uses']) && $handler['uses'] instanceof \Closure) {
            return $this->extractClosureRequestBody($handler['uses']);
        }
        
        return null;
    }

    /**
     * Extract request body from controller method
     */
    protected function extractControllerRequestBody(string $controllerAction)
    {
        try {
            [$controller, $method] = explode('@', $controllerAction);
            
            $reflectionClass = new \ReflectionClass($controller);
            $reflectionMethod = $reflectionClass->getMethod($method);
            
            // Analyze method parameters to find Request classes
            $parameters = $reflectionMethod->getParameters();
            
            foreach ($parameters as $param) {
                $type = $param->getType();
                if ($type && !$type->isBuiltin()) {
                    $typeName = $type->getName();
                    
                    // Check if it's a FormRequest
                    if (class_exists($typeName) && is_subclass_of($typeName, 'Illuminate\Foundation\Http\FormRequest')) {
                        return $this->analyzeFormRequest($typeName, $method);
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore reflection errors
        }
        
        return null;
    }

    /**
     * Extract request body from closure route
     */
    protected function extractClosureRequestBody(\Closure $closure)
    {
        try {
            $reflectionFunction = new \ReflectionFunction($closure);
            $parameters = $reflectionFunction->getParameters();
            
            foreach ($parameters as $param) {
                $type = $param->getType();
                if ($type && !$type->isBuiltin()) {
                    $typeName = $type->getName();
                    
                    // Check if it's a FormRequest
                    if (class_exists($typeName) && is_subclass_of($typeName, 'Illuminate\Foundation\Http\FormRequest')) {
                        return $this->analyzeFormRequest($typeName, 'closure');
                    }
                }
            }
            
            // If no FormRequest found, try to analyze closure source for validation rules
            $filename = $reflectionFunction->getFileName();
            $startLine = $reflectionFunction->getStartLine();
            $endLine = $reflectionFunction->getEndLine();
            
            if ($filename && $startLine && $endLine) {
                $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
                return $this->extractClosureValidationRules($source);
            }
            
        } catch (\Exception $e) {
            // Ignore reflection errors
        }
        
        return null;
    }

    /**
     * Extract validation rules from closure source code
     */
    protected function extractClosureValidationRules(string $source)
    {
        // Clean source code
        $source = preg_replace('/\\/\\*.*?\\*\\//', '', $source); // Remove /* */ comments
        $source = preg_replace('/\\/\\/.*$/', '', $source); // Remove // comments
        
        // Look for $request->validate() calls
        if (preg_match('/\\$\\w+\\s*->\\s*validate\\s*\\(\\s*\\[([^\\]]+)\\]\\s*\\)/s', $source, $matches)) {
            $validationRules = $this->parseValidationRulesFromString($matches[1]);
            if (!empty($validationRules)) {
                return $this->generateRequestBodyFromRules($validationRules);
            }
        }
        
        // Look for Validator::make() calls
        if (preg_match('/Validator\\s*::\\s*make\\s*\\([^,]+,\\s*\\[([^\\]]+)\\]/s', $source, $matches)) {
            $validationRules = $this->parseValidationRulesFromString($matches[1]);
            if (!empty($validationRules)) {
                return $this->generateRequestBodyFromRules($validationRules);
            }
        }
        
        return null;
    }

    /**
     * Parse validation rules from string
     */
    protected function parseValidationRulesFromString(string $rulesString): array
    {
        $rules = array();
        
        // Clean the rules string
        $rulesString = preg_replace('/\s+/', ' ', $rulesString); // Normalize whitespace
        $rulesString = trim($rulesString);
        
        // Handle multi-line array format with better regex
        $pattern = '/([\'"])([^\'"]*)[\'"]\\s*=>\\s*(\\[[^\\]]*\\]|[^,\\]]+)/';
        
        if (preg_match_all($pattern, $rulesString, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $field = $match[2];
                $rule = trim($match[3]);
                
                // Handle array of rules: ['required', 'string', 'max:255']
                if (preg_match('/^\\[(.*)\\]$/', $rule, $arrayMatch)) {
                    $arrayContent = $arrayMatch[1];
                    $ruleItems = array();
                    
                    // Extract quoted strings from array
                    if (preg_match_all('/[\'"]([^\'"]*)[\'"]/', $arrayContent, $ruleMatches)) {
                        $ruleItems = $ruleMatches[1];
                    }
                    
                    $rules[$field] = $ruleItems;
                } else {
                    // Handle quoted string rules
                    $rule = trim($rule, " \t\n\r\0\x0B,'\"");
                    $rules[$field] = $rule;
                }
            }
        }
        
        // If no matches found, try simpler pattern for debugging
        if (empty($rules)) {
            // Try to match simpler patterns
            $simplePattern = '/([\'"])([^\'"]*)[\'"]\\s*=>\\s*([^,]+)/';
            if (preg_match_all($simplePattern, $rulesString, $simpleMatches, PREG_SET_ORDER)) {
                foreach ($simpleMatches as $match) {
                    $field = $match[2];
                    $rule = trim($match[3], " \t\n\r\0\x0B,");
                    
                    // Handle array notation
                    if (strpos($rule, '[') === 0) {
                        // Find the closing bracket
                        $depth = 0;
                        $endPos = 0;
                        for ($i = 0; $i < strlen($rule); $i++) {
                            if ($rule[$i] === '[') $depth++;
                            if ($rule[$i] === ']') $depth--;
                            if ($depth === 0) {
                                $endPos = $i;
                                break;
                            }
                        }
                        
                        if ($endPos > 0) {
                            $arrayContent = substr($rule, 1, $endPos - 1);
                            $ruleItems = array();
                            
                            if (preg_match_all('/[\'"]([^\'"]*)[\'"]/', $arrayContent, $ruleMatches)) {
                                $ruleItems = $ruleMatches[1];
                            }
                            
                            $rules[$field] = $ruleItems;
                        }
                    } else {
                        $rule = trim($rule, " \t\n\r\0\x0B,'\"");
                        $rules[$field] = $rule;
                    }
                }
            }
        }
        
        return $rules;
    }

    /**
     * Generate request body from parsed validation rules
     */
    protected function generateRequestBodyFromRules(array $rules)
    {
        // Generate example based on validation rules
        $example = $this->generateExampleFromRules($rules);
        
        // Get schema from rules
        $schema = $this->generateSchemaFromRules($rules);
        
        return new RequestBody(
            'application/json',
            $schema,
            $example,
            true
        );
    }

    /**
     * Analyze Laravel FormRequest to extract validation rules and generate example
     */
    protected function analyzeFormRequest(string $requestClass, string $method)
    {
        try {
            $reflectionClass = new \ReflectionClass($requestClass);
            
            // Get validation rules
            if ($reflectionClass->hasMethod('rules')) {
                $rulesMethod = $reflectionClass->getMethod('rules');
                
                $rules = null;
                
                // Try to get rules from source code first (more reliable)
                $rules = $this->extractRulesFromFormRequestSource($reflectionClass);
                
                // If source parsing failed, try instantiation
                if (empty($rules)) {
                    try {
                        // Create a mock request for FormRequest instantiation
                        $mockRequest = new \Illuminate\Http\Request();
                        $mockRequest->setMethod('POST');
                        
                        // Try to create instance with minimal dependencies
                        $instance = $reflectionClass->newInstance();
                        
                        // Set the request instance if possible
                        if (method_exists($instance, 'setContainer')) {
                            $instance->setContainer(app());
                        }
                        
                        $rules = $instance->rules();
                    } catch (\Exception $instantiationError) {
                        // If instantiation fails, try static source code parsing
                        $rules = $this->parseRulesFromMethodSource($reflectionClass, $rulesMethod);
                    }
                }
                
                if (!empty($rules)) {
                    // Generate example based on validation rules
                    $example = $this->generateExampleFromRules($rules);
                    
                    // Get schema from rules
                    $schema = $this->generateSchemaFromRules($rules);
                    
                    return new RequestBody(
                        'application/json',
                        $schema,
                        $example,
                        true
                    );
                }
            }
        } catch (\Exception $e) {
            // Ignore errors
        }
        
        return null;
    }

    /**
     * Extract rules from FormRequest source code
     */
    protected function extractRulesFromFormRequestSource(\ReflectionClass $reflectionClass): array
    {
        try {
            $filename = $reflectionClass->getFileName();
            if (!$filename) {
                return array();
            }
            
            $content = file_get_contents($filename);
            
            // Find the rules() method and extract its return array
            if (preg_match('/public\\s+function\\s+rules\\(\\)\\s*:\\s*array\\s*\\{(.*?)\\}/s', $content, $matches)) {
                $methodContent = $matches[1];
                
                // Look for return statement with array
                if (preg_match('/return\\s*\\[(.*?)\\];/s', $methodContent, $returnMatches)) {
                    return $this->parseValidationRulesFromString($returnMatches[1]);
                }
                
                // Look for $rules variable assignment and return
                if (preg_match('/\\$rules\\s*=\\s*\\[(.*?)\\];/s', $methodContent, $rulesMatches)) {
                    return $this->parseValidationRulesFromString($rulesMatches[1]);
                }
            }
        } catch (\Exception $e) {
            // Ignore parsing errors
        }
        
        return array();
    }

    /**
     * Parse rules from method source using reflection
     */
    protected function parseRulesFromMethodSource(\ReflectionClass $reflectionClass, \ReflectionMethod $rulesMethod): array
    {
        try {
            $filename = $reflectionClass->getFileName();
            $startLine = $rulesMethod->getStartLine();
            $endLine = $rulesMethod->getEndLine();
            
            if ($filename && $startLine && $endLine) {
                $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
                
                // Look for return statement with array
                if (preg_match('/return\\s*\\[(.*?)\\];/s', $source, $matches)) {
                    return $this->parseValidationRulesFromString($matches[1]);
                }
                
                // Look for $rules variable assignment
                if (preg_match('/\\$rules\\s*=\\s*\\[(.*?)\\];/s', $source, $matches)) {
                    return $this->parseValidationRulesFromString($matches[1]);
                }
            }
        } catch (\Exception $e) {
            // Ignore parsing errors
        }
        
        return array();
    }

    /**
     * Generate example data from validation rules
     */
    protected function generateExampleFromRules(array $rules): array
    {
        $example = [];
        
        foreach ($rules as $field => $rule) {
            $ruleString = is_array($rule) ? implode('|', $rule) : $rule;
            $example[$field] = $this->generateValueFromRule($field, $ruleString);
        }
        
        return $example;
    }

    /**
     * Generate schema from validation rules
     */
    protected function generateSchemaFromRules(array $rules): array
    {
        $properties = [];
        $required = [];
        
        foreach ($rules as $field => $rule) {
            $ruleString = is_array($rule) ? implode('|', $rule) : $rule;
            $properties[$field] = $this->generatePropertyFromRule($field, $ruleString);
            
            if (str_contains($ruleString, 'required')) {
                $required[] = $field;
            }
        }
        
        return array(
            'type' => 'object',
            'properties' => $properties,
            'required' => $required
        );
    }

    /**
     * Generate example value from validation rule
     */
    protected function generateValueFromRule(string $field, string $rule)
    {
        // Check rule types
        if (str_contains($rule, 'integer') || str_contains($rule, 'numeric')) {
            switch (strtolower($field)) {
                case 'price':
                    return 999.99;
                case 'stock':
                case 'quantity':
                    return 100;
                case 'age':
                    return 25;
                default:
                    return 42;
            }
        }
        
        if (str_contains($rule, 'email')) {
            return 'example@email.com';
        }
        
        if (str_contains($rule, 'boolean')) {
            return true;
        }
        
        if (str_contains($rule, 'array')) {
            return [];
        }
        
        if (str_contains($rule, 'url')) {
            return 'https://example.com';
        }
        
        // Default string values based on field name
        switch (strtolower($field)) {
            case 'name':
            case 'title':
                return 'Example ' . ucfirst($field);
            case 'description':
                return 'Example description';
            case 'password':
                return 'securepassword123';
            case 'email':
                return 'example@email.com';
            default:
                return 'Example value';
        }
    }

    /**
     * Generate property schema from validation rule
     */
    protected function generatePropertyFromRule(string $field, string $rule): array
    {
        $property = [];
        
        if (str_contains($rule, 'integer')) {
            $property['type'] = 'integer';
        } elseif (str_contains($rule, 'numeric')) {
            $property['type'] = 'number';
        } elseif (str_contains($rule, 'boolean')) {
            $property['type'] = 'boolean';
        } elseif (str_contains($rule, 'array')) {
            $property['type'] = 'array';
        } else {
            $property['type'] = 'string';
        }
        
        // Add format for specific types
        if (str_contains($rule, 'email')) {
            $property['format'] = 'email';
        }
        
        if (str_contains($rule, 'url')) {
            $property['format'] = 'uri';
        }
        
        // Extract min/max constraints
        if (preg_match('/min:(\d+)/', $rule, $matches)) {
            if ($property['type'] === 'string') {
                $property['minLength'] = (int)$matches[1];
            } else {
                $property['minimum'] = (int)$matches[1];
            }
        }
        
        if (preg_match('/max:(\d+)/', $rule, $matches)) {
            if ($property['type'] === 'string') {
                $property['maxLength'] = (int)$matches[1];
            } else {
                $property['maximum'] = (int)$matches[1];
            }
        }
        
        return $property;
    }

    /**
     * Generate response examples
     */
    protected function generateResponses($handler, $route = null): array
    {
        $responses = [];
        
        // Try to analyze the controller method to get actual responses
        if (isset($handler['controller'])) {
            $responses = $this->analyzeControllerResponses($handler['controller'], $route);
        } 
        // Handle closure routes
        elseif (isset($handler['uses']) && $handler['uses'] instanceof \Closure) {
            $responses = $this->analyzeClosureResponses($handler['uses'], $route);
        }
        
        // Fallback to default responses if analysis doesn't work
        if (empty($responses)) {
            $responses = [
                '200' => new Response('Success', ['status' => 'success']),
                '400' => new Response('Bad Request'),
                '404' => new Response('Not Found'),
                '500' => new Response('Internal Server Error'),
            ];
        }
        
        return $responses;
    }

    /**
     * Analyze closure routes to extract actual return values
     */
    protected function analyzeClosureResponses(\Closure $closure, $route = null): array
    {
        try {
            $reflectionFunction = new \ReflectionFunction($closure);
            $filename = $reflectionFunction->getFileName();
            $startLine = $reflectionFunction->getStartLine();
            $endLine = $reflectionFunction->getEndLine();
            
            if ($filename && $startLine && $endLine) {
                $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
                
                // Extract actual return value from closure source
                $returnValue = $this->extractClosureReturnValue($source);
                
                if ($returnValue) {
                    return array(
                        '200' => new Response('Success', $returnValue)
                    );
                }
            }
        } catch (\Exception $e) {
            // Ignore reflection errors
        }
        
        return [];
    }

    /**
     * Extract actual return value from closure source code
     */
    protected function extractClosureReturnValue(string $source)
    {
        // Clean source code
        $source = preg_replace('/\\/\\*.*?\\*\\//', '', $source); // Remove /* */ comments
        $source = preg_replace('/\\/\\/.*$/', '', $source); // Remove // comments
        
        // Look for response()->json() patterns and extract the array/object
        if (preg_match('/response\\(\\)\\s*->\\s*json\\s*\\(\\s*([^)]+)\\s*\\)/s', $source, $matches)) {
            $jsonContent = trim($matches[1]);
            
            // Try to parse the JSON content as PHP array
            $parsedData = $this->parsePhpArrayFromString($jsonContent);
            if ($parsedData !== null) {
                return $parsedData;
            }
        }
        
        // Look for direct array returns in json() calls
        if (preg_match('/json\\s*\\(\\s*(\\[.*?\\])\\s*\\)/s', $source, $matches)) {
            $arrayContent = $matches[1];
            $parsedData = $this->parsePhpArrayFromString($arrayContent);
            if ($parsedData !== null) {
                return $parsedData;
            }
        }
        
        // Look for variable returns in json() calls
        if (preg_match('/json\\s*\\(\\s*(\\$\\w+)\\s*\\)/s', $source, $matches)) {
            $variableName = $matches[1];
            
            // Try to find variable assignment in the source
            $pattern = preg_quote($variableName, '/') . '\\s*=\\s*([^;]+);';
            if (preg_match('/' . $pattern . '/s', $source, $varMatches)) {
                $assignmentValue = trim($varMatches[1]);
                $parsedData = $this->parsePhpArrayFromString($assignmentValue);
                if ($parsedData !== null) {
                    return $parsedData;
                }
            }
        }
        
        return null;
    }

    /**
     * Parse PHP array/object string into actual PHP data structure
     */
    protected function parsePhpArrayFromString(string $phpCode)
    {
        try {
            // Clean the PHP code
            $phpCode = trim($phpCode, " \t\n\r\0\x0B,");
            
            // Simple array parsing for basic cases
            if (preg_match('/^\\[.*\\]$/', $phpCode)) {
                // Try to evaluate safely for simple arrays
                $result = $this->safeEvalPhpArray($phpCode);
                if ($result !== null) {
                    return $result;
                }
            }
            
            // Handle compact() calls
            if (preg_match('/compact\\s*\\(\\s*[\'"]([^\'"]+)[\'"]\\s*\\)/', $phpCode, $matches)) {
                $variableName = $matches[1];
                return array($variableName => 'example_value');
            }
            
        } catch (\Exception $e) {
            // Ignore parsing errors
        }
        
        return null;
    }

    /**
     * Parse nested PHP arrays with proper bracket matching
     */
    protected function parseNestedPhpArray(string $arrayString)
    {
        try {
            $arrayString = trim($arrayString);
            
            // Remove outer brackets if present
            if (preg_match('/^\\[(.*)\\]$/', $arrayString, $outerMatch)) {
                $content = $outerMatch[1];
            } else {
                $content = $arrayString;
            }
            
            $result = array();
            $i = 0;
            $len = strlen($content);
            
            while ($i < $len) {
                // Skip whitespace
                while ($i < $len && in_array($content[$i], [' ', "\t", "\n", "\r"])) {
                    $i++;
                }
                
                if ($i >= $len) break;
                
                // Find key
                $key = '';
                if ($content[$i] === '"' || $content[$i] === "'") {
                    $quote = $content[$i];
                    $i++; // Skip opening quote
                    while ($i < $len && $content[$i] !== $quote) {
                        $key .= $content[$i];
                        $i++;
                    }
                    $i++; // Skip closing quote
                } else {
                    // Handle unquoted keys
                    while ($i < $len && !in_array($content[$i], [' ', "\t", "\n", "\r", '='])) {
                        $key .= $content[$i];
                        $i++;
                    }
                }
                
                if (empty($key)) break;
                
                // Skip whitespace and find '=>'
                while ($i < $len && in_array($content[$i], [' ', "\t", "\n", "\r"])) {
                    $i++;
                }
                
                if ($i + 1 < $len && $content[$i] === '=' && $content[$i + 1] === '>') {
                    $i += 2; // Skip '=>'
                } else {
                    break; // Invalid format
                }
                
                // Skip whitespace after '=>'
                while ($i < $len && in_array($content[$i], [' ', "\t", "\n", "\r"])) {
                    $i++;
                }
                
                // Parse value
                if ($i >= $len) break;
                
                $value = null;
                
                if ($content[$i] === '[') {
                    // Parse array value
                    $arrayStart = $i;
                    $bracketCount = 0;
                    
                    do {
                        if ($content[$i] === '[') $bracketCount++;
                        if ($content[$i] === ']') $bracketCount--;
                        $i++;
                    } while ($i < $len && $bracketCount > 0);
                    
                    $arrayContent = substr($content, $arrayStart + 1, $i - $arrayStart - 2);
                    $value = $this->parseArrayItems($arrayContent);
                    
                } elseif ($content[$i] === '"' || $content[$i] === "'") {
                    // Parse string value
                    $quote = $content[$i];
                    $i++; // Skip opening quote
                    $value = '';
                    while ($i < $len && $content[$i] !== $quote) {
                        $value .= $content[$i];
                        $i++;
                    }
                    $i++; // Skip closing quote
                } else {
                    // Parse other values (numbers, booleans, etc.)
                    $valueStr = '';
                    while ($i < $len && !in_array($content[$i], [',', ']'])) {
                        $valueStr .= $content[$i];
                        $i++;
                    }
                    $valueStr = trim($valueStr);
                    
                    if ($valueStr === 'true') $value = true;
                    elseif ($valueStr === 'false') $value = false;
                    elseif ($valueStr === 'null') $value = null;
                    elseif (is_numeric($valueStr)) $value = strpos($valueStr, '.') !== false ? (float)$valueStr : (int)$valueStr;
                    else $value = $valueStr;
                }
                
                $result[$key] = $value;
                
                // Skip to next item
                while ($i < $len && in_array($content[$i], [' ', "\t", "\n", "\r", ','])) {
                    $i++;
                }
            }
            
            return empty($result) ? null : $result;
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Parse array items from string like "'Electronics', 'Books', 'Clothing'"
     */
    protected function parseArrayItems(string $arrayContent)
    {
        $items = array();
        $i = 0;
        $len = strlen($arrayContent);
        
        while ($i < $len) {
            // Skip whitespace and commas
            while ($i < $len && in_array($arrayContent[$i], [' ', "\t", "\n", "\r", ','])) {
                $i++;
            }
            
            if ($i >= $len) break;
            
            $item = '';
            if ($arrayContent[$i] === '"' || $arrayContent[$i] === "'") {
                $quote = $arrayContent[$i];
                $i++; // Skip opening quote
                while ($i < $len && $arrayContent[$i] !== $quote) {
                    $item .= $arrayContent[$i];
                    $i++;
                }
                $i++; // Skip closing quote
                $items[] = $item;
            } else {
                // Handle unquoted items
                while ($i < $len && !in_array($arrayContent[$i], [',', ']'])) {
                    $item .= $arrayContent[$i];
                    $i++;
                }
                $item = trim($item);
                if (!empty($item)) {
                    if ($item === 'true') $items[] = true;
                    elseif ($item === 'false') $items[] = false;
                    elseif ($item === 'null') $items[] = null;
                    elseif (is_numeric($item)) $items[] = strpos($item, '.') !== false ? (float)$item : (int)$item;
                    else $items[] = $item;
                }
            }
        }
        
        return $items;
    }

    /**
     * Safely evaluate simple PHP arrays
     */
    protected function safeEvalPhpArray(string $arrayString)
    {
        try {
            // Handle nested arrays and more complex structures
            $result = array();
            
            // Use a more sophisticated approach to parse the array
            $result = $this->parseNestedPhpArray($arrayString);
            if ($result !== null) {
                return $result;
            }
            
            // First try to match simple key-value pairs with various value types
            if (preg_match_all('/([\'"])([^\'"]*)[\'"]\\s*=>\\s*([^,\\]]+?)(?=,|\\])/s', $arrayString, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $key = $match[2];
                    $value = trim($match[3]);
                    
                    // Handle array values like ['Electronics', 'Books', 'Clothing']
                    if (preg_match('/^\\[(.*)\\]$/', $value, $arrayMatch)) {
                        $arrayContent = $arrayMatch[1];
                        $arrayItems = array();
                        
                        // Extract quoted strings from array
                        if (preg_match_all('/[\'"]([^\'"]*)[\'"]/', $arrayContent, $itemMatches)) {
                            $arrayItems = $itemMatches[1];
                        }
                        $result[$key] = $arrayItems;
                    }
                    // Handle string values
                    elseif (preg_match('/^[\'"]([^\'"]*)[\'"]$/', $value, $strMatch)) {
                        $result[$key] = $strMatch[1];
                    }
                    // Handle boolean values
                    elseif ($value === 'true') {
                        $result[$key] = true;
                    } elseif ($value === 'false') {
                        $result[$key] = false;
                    } elseif ($value === 'null') {
                        $result[$key] = null;
                    }
                    // Handle numeric values
                    elseif (is_numeric($value)) {
                        $result[$key] = strpos($value, '.') !== false ? (float)$value : (int)$value;
                    }
                    // Default string value
                    else {
                        $result[$key] = $value;
                    }
                }
                
                if (!empty($result)) {
                    return $result;
                }
            }
            
            // Fallback: Only allow simple array structures for security
            if (preg_match('/^\\[\\s*([\'"][^\'"]*[\'"]\\s*=>\\s*[\'"][^\'"]*[\'"]\\s*,?\\s*)*\\]$/', $arrayString)) {
                // Parse simple key-value pairs
                if (preg_match_all('/([\'"])([^\'"]*)[\'"]\\s*=>\\s*([\'"])([^\'"]*)[\'"]/', $arrayString, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $key = $match[2];
                        $value = $match[4];
                        $result[$key] = $value;
                    }
                    return $result;
                }
            }
            
        } catch (\Exception $e) {
            // Return null on any error
        }
        
        return null;
    }

    /**
     * Analyze controller method to extract response examples from Laravel Resources
     */
    protected function analyzeControllerResponses(string $controllerAction, $route = null): array
    {
        try {
            [$controller, $method] = explode('@', $controllerAction);
            
            $reflectionClass = new \ReflectionClass($controller);
            $reflectionMethod = $reflectionClass->getMethod($method);
            
            // Get the source code of the method
            $filename = $reflectionClass->getFileName();
            $startLine = $reflectionMethod->getStartLine();
            $endLine = $reflectionMethod->getEndLine();
            
            if ($filename && $startLine && $endLine) {
                $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
                
                // Analyze the source code to find Resource returns
                $resourceInfo = $this->analyzeMethodSource($source, $method);
                
                if ($resourceInfo) {
                    return $this->generateResponsesFromResource($resourceInfo, $route);
                }
            }
        } catch (\Exception $e) {
            // Ignore reflection errors
        }
        
        return [];
    }

    /**
     * Analyze method source code to find Laravel Resources
     */
    protected function analyzeMethodSource(string $source, string $methodName)
    {
        // Clean source code
        $source = preg_replace('/\/\*.*?\*\//', '', $source); // Remove /* */ comments
        $source = preg_replace('/\/\/.*$/', '', $source); // Remove // comments
        
        // Look for Resource returns with various patterns
        if (preg_match('/return\s+new\s+(\w+Resource)\s*\(/i', $source, $matches)) {
            return array(
                'type' => 'single',
                'resource' => $matches[1],
                'method' => $methodName
            );
        }
        
        if (preg_match('/return\s+(\w+Resource)::collection\s*\(/i', $source, $matches)) {
            return array(
                'type' => 'collection',
                'resource' => $matches[1],
                'method' => $methodName
            );
        }
        
        // Look for $resource->response() patterns
        if (preg_match('/(\w+Resource).*?->response\(\)/i', $source, $matches)) {
            return array(
                'type' => 'collection',
                'resource' => $matches[1],
                'method' => $methodName
            );
        }
        
        // Look for response()->json() patterns and extract the content (including multiline)
        if (preg_match('/response\(\)\s*->\s*json\s*\(([^;]+?)\)\s*;/s', $source, $matches)) {
            $jsonContent = trim($matches[1]);
            
            // Handle nested array structures by finding matching brackets
            $openBrackets = 0;
            $foundEnd = false;
            $cleanContent = '';
            
            for ($i = 0; $i < strlen($jsonContent); $i++) {
                $char = $jsonContent[$i];
                $cleanContent .= $char;
                
                if ($char === '[') {
                    $openBrackets++;
                } elseif ($char === ']') {
                    $openBrackets--;
                    if ($openBrackets === 0) {
                        $foundEnd = true;
                        break;
                    }
                } elseif ($char === ')' && $openBrackets === 0) {
                    // Remove the closing parenthesis if we haven't found array closure
                    $cleanContent = substr($cleanContent, 0, -1);
                    break;
                }
            }
            
            $parsedData = $this->parsePhpArrayFromString($cleanContent);
            
            return array(
                'type' => 'json',
                'method' => $methodName,
                'data' => $parsedData ?: ['status' => 'success'] // Fallback if parsing fails
            );
        }
        
        // Look for JsonResponse patterns
        if (preg_match('/JsonResponse/i', $source)) {
            return array(
                'type' => 'json',
                'method' => $methodName
            );
        }
        
        return null;
    }

    /**
     * Generate responses based on Laravel Resource analysis
     */
    protected function generateResponsesFromResource(array $resourceInfo, $route = null): array
    {
        $responses = [];
        
        if ($resourceInfo['type'] === 'single') {
            $example = $this->generateResourceExample($resourceInfo['resource'], false, null);
            $responses['200'] = new Response('Success', $example);
        } elseif ($resourceInfo['type'] === 'collection') {
            // Try to get route context for better URL generation
            $routeContext = $this->extractRouteContext($resourceInfo, $route);
            $example = $this->generateResourceExample($resourceInfo['resource'], true, $routeContext);
            $responses['200'] = new Response('Success', $example);
        } elseif ($resourceInfo['type'] === 'json') {
            // Use the actual extracted JSON data
            $example = isset($resourceInfo['data']) ? $resourceInfo['data'] : ['status' => 'success'];
            $responses['200'] = new Response('Success', $example);
        }
        
        // Add common error responses
        $method = $resourceInfo['method'] ?? '';
        
        if (str_contains($method, 'show') || str_contains($method, 'update') || str_contains($method, 'destroy')) {
            $responses['404'] = new Response('Not Found', ['error' => 'Resource not found']);
        }
        
        if (str_contains($method, 'store') || str_contains($method, 'update')) {
            $responses['422'] = new Response('Validation Error', [
                'message' => 'The given data was invalid.',
                'errors' => []
            ]);
        }
        
        return $responses;
    }

    /**
     * Extract route context from resource info for better URL generation
     */
    protected function extractRouteContext(array $resourceInfo, $route = null): array
    {
        $path = '/api/v1/resource'; // Default path
        
        if ($route && method_exists($route, 'path')) {
            $path = $route->path;
        } elseif ($route && is_object($route) && property_exists($route, 'path')) {
            $path = $route->path;
        } elseif (is_array($route) && isset($route['path'])) {
            $path = $route['path'];
        }
        
        return array(
            'path' => $path,
            'method' => $resourceInfo['method'] ?? 'index'
        );
    }

    /**
     * Generate example data based on Resource class
     */
    protected function generateResourceExample(string $resourceClass, bool $isCollection = false, $routeContext = null): array
    {
        try {
            // Try to find the resource class
            $fullResourceClass = "App\\Http\\Resources\\{$resourceClass}";
            
            if (class_exists($fullResourceClass)) {
                $reflectionClass = new \ReflectionClass($fullResourceClass);
                $toArrayMethod = $reflectionClass->getMethod('toArray');
                
                // Get the source code of toArray method
                $filename = $reflectionClass->getFileName();
                $startLine = $toArrayMethod->getStartLine();
                $endLine = $toArrayMethod->getEndLine();
                
                if ($filename && $startLine && $endLine) {
                    $source = implode('', array_slice(file($filename), $startLine - 1, $endLine - $startLine + 1));
                    $structure = $this->parseResourceStructure($source);
                    
                    if ($isCollection) {
                        return $this->generatePaginationResponse([$structure, $structure], $routeContext);
                    } else {
                        return ['data' => $structure];
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore errors
        }
        
        // Fallback example
        $fallback = [
            'id' => '01K3JWTD2MWDJZ6K4WY1DCH7FW',
            'name' => 'Example Item',
            'created_at' => '2025-08-26T09:54:47+00:00',
            'updated_at' => '2025-08-26T09:54:47+00:00'
        ];
        
        if ($isCollection) {
            return $this->generatePaginationResponse([$fallback], $routeContext);
        } else {
            return array('data' => $fallback);
        }
    }

    /**
     * Generate Laravel pagination response structure
     */
    protected function generatePaginationResponse(array $data, $routeContext = null)
    {
        // Get current app URL or use localhost as fallback
        $baseUrl = config('app.url', 'http://127.0.0.1:8000');
        $resourcePath = ($routeContext && isset($routeContext['path'])) 
            ? $routeContext['path'] 
            : '/api/v1/resource';
        
        // Generate realistic pagination values
        $total = count($data) > 10 ? rand(50, 200) : count($data);
        $perPage = 15;
        $currentPage = 1;
        $lastPage = max(1, ceil($total / $perPage));
        $from = $total > 0 ? 1 : null;
        $to = min($perPage, $total);
        
        return array(
            'data' => $data,
            'links' => array(
                'first' => $baseUrl . $resourcePath . '?page=1',
                'last' => $baseUrl . $resourcePath . '?page=' . $lastPage,
                'prev' => null,
                'next' => $lastPage > 1 ? $baseUrl . $resourcePath . '?page=2' : null
            ),
            'meta' => array(
                'current_page' => $currentPage,
                'from' => $from,
                'last_page' => $lastPage,
                'links' => array(
                    array(
                        'url' => null,
                        'label' => '« Previous',
                        'page' => null,
                        'active' => false
                    ),
                    array(
                        'url' => $baseUrl . $resourcePath . '?page=1',
                        'label' => '1',
                        'page' => 1,
                        'active' => true
                    ),
                    array(
                        'url' => $lastPage > 1 ? $baseUrl . $resourcePath . '?page=2' : null,
                        'label' => 'Next »',
                        'page' => $lastPage > 1 ? 2 : null,
                        'active' => false
                    )
                ),
                'path' => $baseUrl . $resourcePath,
                'per_page' => $perPage,
                'to' => $to,
                'total' => $total
            )
        );
    }

    /**
     * Parse Resource toArray method to extract structure
     */
    protected function parseResourceStructure(string $source): array
    {
        $structure = [];
        
        // Extract return array structure - handle multiline arrays better
        if (preg_match('/return\s+\[(.*?)\];/s', $source, $matches)) {
            $arrayContent = $matches[1];
            
            // Clean up the array content
            $arrayContent = preg_replace('/\/\*.*?\*\//', '', $arrayContent); // Remove /* */ comments
            $arrayContent = preg_replace('/\/\/.*$/', '', $arrayContent); // Remove // comments
            
            // Parse key-value pairs with better regex
            if (preg_match_all('/[\'"](\w+)[\'"]?\s*=>\s*([^,\n]+?)(?=,|\s*[\'"]|\s*\])/s', $arrayContent, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $key = $match[1];
                    $value = trim($match[2], " \t\n\r\0\x0B,");
                    
                    // Generate sample values based on key names and patterns
                    $structure[$key] = $this->generateSampleValue($key, $value);
                }
            }
        }
        
        // If parsing failed, try to extract from class reflection
        if (empty($structure)) {
            $structure = $this->getDefaultResourceStructure();
        }
        
        return $structure;
    }

    /**
     * Get default resource structure as fallback
     */
    protected function getDefaultResourceStructure(): array
    {
        return [
            'id' => '01K3JWTD2MWDJZ6K4WY1DCH7FW',
            'name' => 'Example Item',
            'created_at' => '2025-08-26T09:54:47+00:00',
            'updated_at' => '2025-08-26T09:54:47+00:00'
        ];
    }

    /**
     * Generate sample value based on key name and pattern
     */
    protected function generateSampleValue(string $key, string $pattern)
    {
        // Handle specific patterns
        if (str_contains($pattern, '$this->id')) {
            return '01K3JWTD2MWDJZ6K4WY1DCH7FW';
        }
        
        if (str_contains($pattern, '$this->name')) {
            return 'Example ' . ucfirst($key);
        }
        
        if (str_contains($pattern, '$this->price')) {
            return 999.99;
        }
        
        if (str_contains($pattern, '$this->stock')) {
            return 100;
        }
        
        if (str_contains($pattern, '$this->description')) {
            return 'Example description for ' . $key;
        }
        
        if (str_contains($pattern, 'created_at') || str_contains($pattern, 'updated_at')) {
            return '2025-08-26T09:54:47+00:00';
        }
        
        if (str_contains($pattern, 'toIso8601String')) {
            return '2025-08-26T09:54:47+00:00';
        }
        
        if (str_contains($pattern, '(float)') || str_contains($pattern, 'float')) {
            return 99.99;
        }
        
        if (str_contains($pattern, '(int)') || str_contains($pattern, 'int')) {
            return 42;
        }
        
        // Default based on key name
        switch (strtolower($key)) {
            case 'id':
                return '01K3JWTD2MWDJZ6K4WY1DCH7FW';
            case 'name':
            case 'title':
                return 'Example Item';
            case 'email':
                return 'example@email.com';
            case 'price':
                return 999.99;
            case 'stock':
            case 'quantity':
                return 100;
            case 'description':
                return 'Example description';
            case 'created_at':
            case 'updated_at':
                return '2025-08-26T09:54:47+00:00';
            default:
                return null;
        }
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
        switch (strtoupper($method)) {
            case 'GET':
                return str_contains($path, '{') ? 'Get' : 'List';
            case 'POST':
                return 'Create';
            case 'PUT':
            case 'PATCH':
                return 'Update';
            case 'DELETE':
                return 'Delete';
            default:
                return $method;
        }
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
     * Refresh routes by rediscovering them from Laravel router
     */
    public function refreshRoutes(): void
    {
        // Clear existing routes
        $this->routes = [];
        
        // Re-scan Laravel routes
        $routes = \Route::getRoutes();
        
        foreach ($routes as $route) {
            // Use same logic as service provider
            if ($this->shouldIncludeRoute($route)) {
                $this->addRoute($route);
            }
        }
    }
    
    /**
     * Check if route should be included (simplified version from service provider)
     */
    protected function shouldIncludeRoute($route): bool
    {
        $uri = $route->uri();
        $methods = $route->methods();
        
        // Skip HEAD and OPTIONS methods
        if (in_array('HEAD', $methods) && count($methods) === 1) {
            return false;
        }
        
        // Skip ByteDocs routes
        $docsPath = trim(config('bytedocs.docs_path', '/docs'), '/');
        if (str_starts_with($uri, $docsPath)) {
            return false;
        }
        
        // Skip Laravel system routes
        $systemRoutes = ['_ignition', 'sanctum', 'api/documentation', 'telescope', 'horizon'];
        foreach ($systemRoutes as $systemRoute) {
            if (str_starts_with($uri, $systemRoute)) {
                return false;
            }
        }
        
        // Check route detection mode
        $mode = config('bytedocs.route_detection.mode', 'both');
        if ($mode === 'api') {
            // Only include API routes
            return str_starts_with($uri, 'api/') || in_array('api', $route->middleware());
        } elseif ($mode === 'web') {
            // Only include web routes (non-API)
            return !str_starts_with($uri, 'api/') && !in_array('api', $route->middleware());
        }
        
        // Default: include all non-system routes
        return true;
    }

    /**
     * Get API context for LLM (optimized for token usage)
     */
    public function getAPIContext(string $userQuestion = ''): string
    {
        // Check cache first for similar questions
        $cacheKey = $this->getCacheKey($userQuestion);
        $cachedContext = $this->getCachedContext($cacheKey);
        
        if ($cachedContext && !$this->shouldBypassCache($userQuestion)) {
            \Log::info('🚀 Context Cache HIT', ['cache_key' => substr($cacheKey, 0, 20), 'saved_processing' => true]);
            return $cachedContext;
        }

        // Force fresh route discovery for LLM context
        $this->refreshRoutes();
        
        $openAPIJSON = $this->getOpenAPIJSON();
        
        // Smart context optimization based on user question
        $optimizedContext = $this->getSmartOptimizedContext($openAPIJSON, $userQuestion);

        $contextText = sprintf(
            "API: %s v%s | %s | %s

%s

RULES: Use only listed endpoints/params. No invention.",
            $this->documentation->info->title,
            $this->documentation->info->version,
            $this->documentation->info->description,
            $this->config->baseURLs[0]['url'] ?? 'localhost:8000',
            $optimizedContext
        );

        // Cache the context for similar future questions
        $this->cacheContext($cacheKey, $contextText);
        \Log::info('🔄 Context Cache MISS', ['cache_key' => substr($cacheKey, 0, 20), 'cached_for_future' => true]);

        return $contextText;
    }

    /**
     * Generate cache key based on question semantics
     */
    protected function getCacheKey(string $userQuestion): string
    {
        $question = strtolower(trim($userQuestion));
        
        // Normalize question to catch semantic similarities
        $normalizedQuestion = preg_replace([
            '/\b(how to|how do i|how can i)\b/',
            '/\b(please|can you|could you)\b/', 
            '/\b(example|sample|demo)\b/',
            '/\b(payload|request body|json)\b/',
            '/\b(response|return|output)\b/',
            '/[^\w\s]/', // Remove punctuation
            '/\s+/' // Normalize whitespace
        ], [
            'howto',
            '',
            'example', 
            'payload',
            'response',
            ' ',
            ' '
        ], $question);
        
        // Extract key concepts for semantic grouping
        $concepts = [];
        $keyWords = ['user', 'product', 'auth', 'login', 'register', 'create', 'update', 'delete', 'list', 'get', 'post', 'put'];
        foreach ($keyWords as $word) {
            if (str_contains($normalizedQuestion, $word)) {
                $concepts[] = $word;
            }
        }
        
        return 'api_context_' . md5(implode('_', $concepts) . '_' . trim($normalizedQuestion));
    }

    /**
     * Get cached context if available and fresh
     */
    protected function getCachedContext(string $cacheKey): ?string
    {
        try {
            if (function_exists('cache')) {
                return cache()->get($cacheKey);
            }
        } catch (\Exception $e) {
            \Log::warning('Context cache read failed', ['error' => $e->getMessage()]);
        }
        
        return null;
    }

    /**
     * Cache context for future use
     */
    protected function cacheContext(string $cacheKey, string $context): void
    {
        try {
            if (function_exists('cache')) {
                // Cache for 1 hour - context may change as API evolves
                cache()->put($cacheKey, $context, 3600);
            }
        } catch (\Exception $e) {
            \Log::warning('Context cache write failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Check if we should bypass cache (for real-time questions)
     */
    protected function shouldBypassCache(string $userQuestion): bool
    {
        $realTimeKeywords = ['current', 'latest', 'new', 'recent', 'updated', 'changed'];
        $question = strtolower($userQuestion);
        
        foreach ($realTimeKeywords as $keyword) {
            if (str_contains($question, $keyword)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get smart optimized context based on user question
     */
    protected function getSmartOptimizedContext(array $openAPIJSON, string $userQuestion): string
    {
        if (empty($openAPIJSON['paths'])) {
            return "=== ENDPOINTS ===\nNo API endpoints found.\n";
        }

        // Analyze user question to determine relevant endpoints
        $relevantPaths = $this->findRelevantEndpoints($openAPIJSON['paths'], $userQuestion);
        
        // If specific endpoints found, prioritize them; otherwise show all with limit
        $maxEndpoints = !empty($relevantPaths) ? 10 : 8; // More if relevant, fewer if showing all
        
        return $this->formatOptimizedContext($openAPIJSON, $relevantPaths, $maxEndpoints);
    }

    /**
     * Find endpoints relevant to user question with advanced analysis
     */
    protected function findRelevantEndpoints(array $paths, string $userQuestion): array
    {
        if (empty($userQuestion)) {
            return [];
        }

        $question = strtolower($userQuestion);
        $questionType = $this->analyzeQuestionType($question);
        $relevantPaths = [];
        $score = [];

        foreach ($paths as $path => $methods) {
            $pathScore = 0;
            
            // Enhanced path keyword scoring
            $pathWords = preg_split('/[\/\-_]/', strtolower($path));
            foreach ($pathWords as $word) {
                if ($word && str_contains($question, $word)) {
                    $pathScore += 15; // Increased score for exact path matches
                }
            }

            foreach ($methods as $method => $operation) {
                $operationScore = $pathScore;
                
                // Question-type specific scoring
                $methodScore = $this->scoreMethodByQuestionType($method, $questionType);
                $operationScore += $methodScore;
                
                // Enhanced keyword matching with context
                $searchText = strtolower(implode(' ', [
                    $operation['summary'] ?? '',
                    $operation['description'] ?? '',
                    $operation['operationId'] ?? '',
                    $method
                ]));

                // Semantic keyword groups
                $keywordGroups = [
                    'auth' => ['auth', 'login', 'register', 'signin', 'signup', 'token', 'password'],
                    'crud' => ['create', 'update', 'delete', 'edit', 'remove', 'add', 'new'],
                    'list' => ['list', 'get', 'fetch', 'retrieve', 'show', 'find', 'search'],
                    'payload' => ['payload', 'request', 'body', 'data', 'json', 'params'],
                    'response' => ['response', 'return', 'output', 'result', 'format']
                ];

                foreach ($keywordGroups as $group => $keywords) {
                    foreach ($keywords as $keyword) {
                        if (str_contains($question, $keyword)) {
                            foreach ($keywords as $relatedKeyword) {
                                if (str_contains($searchText, $relatedKeyword)) {
                                    $operationScore += 8; // Higher score for semantic matches
                                    break 2;
                                }
                            }
                        }
                    }
                }

                if ($operationScore > 0) {
                    $key = $path . '::' . $method;
                    $relevantPaths[$key] = ['path' => $path, 'method' => $method, 'operation' => $operation];
                    $score[$key] = $operationScore;
                }
            }
        }

        // Sort by relevance score
        arsort($score);
        $sortedPaths = [];
        foreach (array_keys($score) as $key) {
            $sortedPaths[$key] = $relevantPaths[$key];
        }

        // Dynamic limit based on question complexity
        $limit = $this->getDynamicEndpointLimit($question, $questionType);
        return array_slice($sortedPaths, 0, $limit, true);
    }

    /**
     * Analyze question type for better context optimization
     */
    protected function analyzeQuestionType(string $question): string
    {
        $patterns = [
            'payload' => '/payload|request|body|json|data|params|send|post|put|patch/',
            'response' => '/response|return|get|output|result|format|structure/',
            'auth' => '/auth|login|register|token|signin|signup|password/',
            'list' => '/list|all|endpoints|available|what.*can/',
            'example' => '/example|sample|demo|how.*to|tutorial/',
            'specific' => '/specific|particular|this|that/'
        ];

        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $question)) {
                return $type;
            }
        }

        return 'general';
    }

    /**
     * Score HTTP methods based on question type
     */
    protected function scoreMethodByQuestionType(string $method, string $questionType): int
    {
        $methodScores = [
            'payload' => ['post' => 15, 'put' => 12, 'patch' => 10, 'get' => -5],
            'response' => ['get' => 15, 'post' => 8, 'put' => 8, 'delete' => 5],
            'auth' => ['post' => 15, 'get' => 5],
            'list' => ['get' => 15, 'post' => -5, 'put' => -5, 'delete' => -5]
        ];

        $method = strtolower($method);
        return $methodScores[$questionType][$method] ?? 0;
    }

    /**
     * Get dynamic endpoint limit based on question complexity
     */
    protected function getDynamicEndpointLimit(string $question, string $questionType): int
    {
        // Simple questions get fewer endpoints
        if (in_array($questionType, ['payload', 'response']) && 
            preg_match('/\b(one|single|specific|this|that)\b/', $question)) {
            return 3; // Ultra-focused
        }

        // List questions need more endpoints
        if ($questionType === 'list' || str_contains($question, 'all')) {
            return 12; // Show more for overview
        }

        // Complex questions get moderate amount
        if (str_contains($question, 'how') || str_contains($question, 'tutorial')) {
            return 8; // Detailed explanation
        }

        return 6; // Default balanced amount
    }

    /**
     * Format optimized context with endpoint limits
     */
    protected function formatOptimizedContext(array $openAPIJSON, array $relevantPaths, int $maxEndpoints): string
    {
        $output = "";
        $endpointCount = 0;
        
        // Prioritize relevant endpoints first (no section headers)
        if (!empty($relevantPaths)) {
            foreach ($relevantPaths as $data) {
                if ($endpointCount >= $maxEndpoints) break;
                $output .= $this->formatSingleEndpoint($data['path'], $data['method'], $data['operation']);
                $endpointCount++;
            }
        }
        
        // Add other endpoints if space available
        if ($endpointCount < $maxEndpoints && (empty($relevantPaths) || $endpointCount < 3)) {
            foreach ($openAPIJSON['paths'] as $path => $methods) {
                if ($endpointCount >= $maxEndpoints) break;
                
                foreach ($methods as $method => $operation) {
                    if ($endpointCount >= $maxEndpoints) break;
                    
                    // Skip if already included
                    $key = $path . '::' . $method;
                    if (isset($relevantPaths[$key])) continue;
                    
                    $output .= $this->formatSingleEndpoint($path, $method, $operation);
                    $endpointCount++;
                }
            }
        }
        
        return $output;
    }

    /**
     * Format a single endpoint in EXTREME compact format
     */
    protected function formatSingleEndpoint(string $path, string $method, array $operation): string
    {
        $method = strtoupper($method);
        $summary = $operation['summary'] ?? '';
        
        // EXTREME compression: no newlines, minimal separators
        $output = "{$method} {$path}";
        if ($summary) $output .= "-{$summary}";
        
        // EXTREME compact parameters
        if (!empty($operation['parameters'])) {
            $params = [];
            foreach (array_slice($operation['parameters'], 0, 4) as $param) { // Reduced to 4
                $required = ($param['required'] ?? false) ? '*' : '';
                $type = $param['schema']['type'] ?? 'string';
                
                // Only show type for non-strings
                if ($type === 'string' || $this->isCommonStringParam($param['name'])) {
                    $params[] = "{$param['name']}{$required}";
                } else {
                    $params[] = "{$param['name']}{$required}({$type})";
                }
            }
            $output .= " ?=" . implode(',', $params); // Use ?= instead of emoji
        }
        
        // EXTREME compact request body
        if (!empty($operation['requestBody'])) {
            $schema = $operation['requestBody']['content']['application/json']['schema'] ?? [];
            if (!empty($schema['properties'])) {
                $props = [];
                foreach (array_slice($schema['properties'], 0, 5, true) as $prop => $details) { // Reduced to 5
                    $required = in_array($prop, $schema['required'] ?? []) ? '*' : '';
                    $type = $details['type'] ?? 'string';
                    
                    // Only show type for non-strings
                    if ($this->isCommonStringField($prop) && $type === 'string') {
                        $props[] = "{$prop}{$required}";
                    } else {
                        $props[] = "{$prop}{$required}({$type})";
                    }
                }
                $output .= " {" . implode(',', $props) . "}"; // Use {} instead of emoji
            }
        }
        
        // EXTREME compact responses (only 2 most important)
        if (!empty($operation['responses'])) {
            $codes = [];
            $important = ['200', '201', '422', '404']; // Only most critical
            foreach ($important as $code) {
                if (isset($operation['responses'][$code])) {
                    $codes[] = $code . $this->getCompactStatusIcon($code);
                }
            }
            if (!empty($codes)) {
                $output .= " ->" . implode(',', array_slice($codes, 0, 2)); // Max 2 responses
            }
        }
        
        return "\n" . $output; // Single newline only
    }

    /**
     * Get ultra-compact status icons
     */
    protected function getCompactStatusIcon(string $code): string
    {
        return match($code) {
            '200', '201' => '✓',
            '422' => '✗', 
            '404' => '?',
            '401' => '!',
            default => ''
        };
    }

    /**
     * Check if parameter name suggests string type (for ultra-compact format)
     */
    protected function isCommonStringParam(string $name): bool
    {
        $stringParams = ['search', 'filter', 'sort', 'order', 'query', 'name', 'email', 'status', 'type'];
        return in_array(strtolower($name), $stringParams);
    }

    /**
     * Check if field name suggests string type (for ultra-compact format)  
     */
    protected function isCommonStringField(string $name): bool
    {
        $stringFields = ['name', 'email', 'title', 'description', 'status', 'type', 'category', 'slug', 'content'];
        return in_array(strtolower($name), $stringFields);
    }

    /**
     * Abbreviate response descriptions for compact display
     */
    protected function abbreviateResponseDesc(string $desc): string
    {
        $abbreviations = [
            'successful' => 'OK',
            'created successfully' => 'Created', 
            'validation error' => 'Invalid',
            'unauthorized' => 'Unauth',
            'not found' => 'NotFound',
            'internal server error' => 'Error'
        ];
        
        $desc = strtolower($desc);
        foreach ($abbreviations as $full => $short) {
            if (str_contains($desc, $full)) {
                return $short;
            }
        }
        
        return ucfirst(substr($desc, 0, 8)); // Fallback: first 8 chars
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
            $request->context = $this->getAPIContext($request->message);
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