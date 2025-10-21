<?php

namespace ByteDocs\Laravel\Parser;

use ReflectionClass;
use ReflectionException;

class ModelAnalyzer
{
    /**
     * Analyze Eloquent model and extract its structure
     */
    public function analyzeModel(string $modelClass): ?array
    {
        try {
            if (!class_exists($modelClass)) {
                return null;
            }

            $reflection = new ReflectionClass($modelClass);

            // Check if it's an Eloquent model
            if (!$this->isEloquentModel($reflection)) {
                return null;
            }

            return $this->extractModelStructure($reflection);
        } catch (ReflectionException $e) {
            return null;
        }
    }

    /**
     * Check if class is an Eloquent model
     */
    protected function isEloquentModel(ReflectionClass $reflection): bool
    {
        $parentClass = $reflection->getParentClass();

        while ($parentClass) {
            if ($parentClass->getName() === 'Illuminate\Database\Eloquent\Model') {
                return true;
            }
            $parentClass = $parentClass->getParentClass();
        }

        return false;
    }

    /**
     * Extract model structure from reflection
     */
    protected function extractModelStructure(ReflectionClass $reflection): array
    {
        $structure = [];

        // Get fillable attributes
        $fillable = $this->getFillableAttributes($reflection);

        // Get casts for type information
        $casts = $this->getCasts($reflection);

        // Get hidden and visible attributes
        $hidden = $this->getHiddenAttributes($reflection);

        // Build structure
        foreach ($fillable as $attribute) {
            if (in_array($attribute, $hidden)) {
                continue; // Skip hidden attributes
            }

            $type = $this->getAttributeType($attribute, $casts);
            $structure[$attribute] = $this->generateExampleValue($attribute, $type);
        }

        // Add timestamps if model uses them
        if ($this->usesTimestamps($reflection)) {
            if (!in_array('created_at', $hidden)) {
                $structure['created_at'] = '2025-10-21T04:37:33.000000Z';
            }
            if (!in_array('updated_at', $hidden)) {
                $structure['updated_at'] = '2025-10-21T04:37:33.000000Z';
            }
        }

        // Add id if not hidden
        if (!in_array('id', $hidden)) {
            $structure = ['id' => 1] + $structure;
        }

        return $structure;
    }

    /**
     * Get fillable attributes from model
     */
    protected function getFillableAttributes(ReflectionClass $reflection): array
    {
        try {
            $fillableProperty = $reflection->getProperty('fillable');
            $fillableProperty->setAccessible(true);

            $instance = $this->createModelInstance($reflection);
            if ($instance) {
                $fillable = $fillableProperty->getValue($instance);
                return is_array($fillable) ? $fillable : [];
            }
        } catch (\Exception $e) {
            // Try to parse from source code
            return $this->parseFillableFromSource($reflection);
        }

        return [];
    }

    /**
     * Get casts from model
     */
    protected function getCasts(ReflectionClass $reflection): array
    {
        try {
            if ($reflection->hasProperty('casts')) {
                $castsProperty = $reflection->getProperty('casts');
                $castsProperty->setAccessible(true);

                $instance = $this->createModelInstance($reflection);
                if ($instance) {
                    $casts = $castsProperty->getValue($instance);
                    return is_array($casts) ? $casts : [];
                }
            }
        } catch (\Exception $e) {
            // Try to parse from source code
            return $this->parseCastsFromSource($reflection);
        }

        return [];
    }

    /**
     * Get hidden attributes from model
     */
    protected function getHiddenAttributes(ReflectionClass $reflection): array
    {
        try {
            if ($reflection->hasProperty('hidden')) {
                $hiddenProperty = $reflection->getProperty('hidden');
                $hiddenProperty->setAccessible(true);

                $instance = $this->createModelInstance($reflection);
                if ($instance) {
                    $hidden = $hiddenProperty->getValue($instance);
                    return is_array($hidden) ? $hidden : [];
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return [];
    }

    /**
     * Check if model uses timestamps
     */
    protected function usesTimestamps(ReflectionClass $reflection): bool
    {
        try {
            if ($reflection->hasProperty('timestamps')) {
                $timestampsProperty = $reflection->getProperty('timestamps');
                $timestampsProperty->setAccessible(true);

                $instance = $this->createModelInstance($reflection);
                if ($instance) {
                    return $timestampsProperty->getValue($instance) !== false;
                }
            }
        } catch (\Exception $e) {
            // Default to true for Eloquent models
        }

        return true;
    }

    /**
     * Create model instance safely
     */
    protected function createModelInstance(ReflectionClass $reflection)
    {
        try {
            return $reflection->newInstanceWithoutConstructor();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse fillable from source code
     */
    protected function parseFillableFromSource(ReflectionClass $reflection): array
    {
        try {
            $filename = $reflection->getFileName();
            if (!$filename) {
                return [];
            }

            $content = file_get_contents($filename);

            // Match: protected $fillable = ['field1', 'field2'];
            if (preg_match('/protected\s+\$fillable\s*=\s*\[(.*?)\];/s', $content, $matches)) {
                $arrayContent = $matches[1];

                // Extract quoted strings
                if (preg_match_all('/[\'"]([^\'"]+)[\'"]/', $arrayContent, $fieldMatches)) {
                    return $fieldMatches[1];
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return [];
    }

    /**
     * Parse casts from source code
     */
    protected function parseCastsFromSource(ReflectionClass $reflection): array
    {
        try {
            $filename = $reflection->getFileName();
            if (!$filename) {
                return [];
            }

            $content = file_get_contents($filename);

            // Match: protected $casts = ['field' => 'type'];
            if (preg_match('/protected\s+\$casts\s*=\s*\[(.*?)\];/s', $content, $matches)) {
                $arrayContent = $matches[1];

                // Extract key-value pairs
                $casts = [];
                if (preg_match_all('/[\'"]([^\'"]+)[\'"]\s*=>\s*[\'"]([^\'"]+)[\'"]/', $arrayContent, $castMatches, PREG_SET_ORDER)) {
                    foreach ($castMatches as $match) {
                        $casts[$match[1]] = $match[2];
                    }
                }
                return $casts;
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return [];
    }

    /**
     * Get attribute type from casts or infer from name
     */
    protected function getAttributeType(string $attribute, array $casts): string
    {
        if (isset($casts[$attribute])) {
            return $casts[$attribute];
        }

        // Infer from attribute name
        $lowerAttr = strtolower($attribute);

        if (str_contains($lowerAttr, 'email')) {
            return 'string';
        }

        if (str_contains($lowerAttr, 'price') || str_contains($lowerAttr, 'amount')) {
            return 'float';
        }

        if (str_contains($lowerAttr, 'count') || str_contains($lowerAttr, 'quantity') || str_contains($lowerAttr, 'stock')) {
            return 'integer';
        }

        if (str_contains($lowerAttr, 'is_') || str_contains($lowerAttr, 'has_')) {
            return 'boolean';
        }

        if (str_contains($lowerAttr, 'date') || str_contains($lowerAttr, '_at')) {
            return 'datetime';
        }

        return 'string';
    }

    /**
     * Generate example value based on attribute name and type
     */
    protected function generateExampleValue(string $attribute, string $type)
    {
        $lowerAttr = strtolower($attribute);

        switch ($type) {
            case 'boolean':
            case 'bool':
                return false;

            case 'integer':
            case 'int':
                if (str_contains($lowerAttr, 'price')) {
                    return 1000;
                }
                if (str_contains($lowerAttr, 'stock') || str_contains($lowerAttr, 'quantity')) {
                    return 100;
                }
                return 1;

            case 'float':
            case 'double':
            case 'decimal':
                return 99.99;

            case 'array':
            case 'json':
                return [];

            case 'datetime':
            case 'timestamp':
                return '2025-10-21T04:37:33.000000Z';

            default:
                // String types
                if (str_contains($lowerAttr, 'email')) {
                    return 'example@email.com';
                }
                if (str_contains($lowerAttr, 'url')) {
                    return 'https://example.com';
                }
                if (str_contains($lowerAttr, 'title')) {
                    return 'Example Title';
                }
                if (str_contains($lowerAttr, 'name')) {
                    return 'Example Name';
                }
                if (str_contains($lowerAttr, 'description')) {
                    return 'Example description';
                }

                return 'example';
        }
    }

    /**
     * Detect model class from variable or method return type
     */
    public function detectModelFromCode(string $source, string $variableName): ?string
    {
        // Look for the specific variable assignment with Model pattern
        // Pattern: $variableName = ModelClass::...
        $specificPattern = '/\$' . preg_quote($variableName, '/') . '\s*=\s*(\w+)::(?:query|all|find|where|first|get|orderBy|latest|oldest|create|findOrFail|firstOrFail|paginate|simplePaginate)/';

        if (preg_match($specificPattern, $source, $matches)) {
            $className = $matches[1];

            // Try to resolve full class name
            $possibleClasses = [
                "App\\Models\\{$className}",
                "App\\{$className}",
                $className,
            ];

            foreach ($possibleClasses as $fullClass) {
                if (class_exists($fullClass)) {
                    return $fullClass;
                }
            }
        }

        // Also try chained methods: $variable = Model::where()->orderBy()->get()
        $chainedPattern = '/\$' . preg_quote($variableName, '/') . '\s*=\s*(\w+)::[^;]+/';
        if (preg_match($chainedPattern, $source, $matches)) {
            $className = $matches[1];

            $possibleClasses = [
                "App\\Models\\{$className}",
                "App\\{$className}",
                $className,
            ];

            foreach ($possibleClasses as $fullClass) {
                if (class_exists($fullClass)) {
                    return $fullClass;
                }
            }
        }

        // Fallback: Try to find any Model usage in the method
        $genericPatterns = [
            // Model::query(), Model::all(), Model::find(), Model::where()
            '/(\w+)::(?:query|all|find|where|first|orderBy|latest)\s*\(/',
            // new Model()
            '/new\s+(\w+)\s*\(/',
            // Model::create()
            '/(\w+)::create\s*\(/',
        ];

        foreach ($genericPatterns as $pattern) {
            if (preg_match($pattern, $source, $matches)) {
                $className = $matches[1];

                // Skip common non-model classes
                $skipClasses = ['Request', 'Response', 'Validator', 'DB', 'Auth', 'Hash', 'Cache', 'Log', 'Event', 'Mail'];
                if (in_array($className, $skipClasses)) {
                    continue;
                }

                // Try to resolve full class name
                $possibleClasses = [
                    "App\\Models\\{$className}",
                    "App\\{$className}",
                    $className,
                ];

                foreach ($possibleClasses as $fullClass) {
                    if (class_exists($fullClass)) {
                        return $fullClass;
                    }
                }
            }
        }

        return null;
    }
}
