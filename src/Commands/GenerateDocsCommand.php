<?php

namespace ByteDocs\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use ByteDocs\Laravel\Core\APIDocs;

class GenerateDocsCommand extends Command
{
    protected $signature = 'bytedocs:generate {--debug : Show debug information}';
    protected $description = 'Generate ByteDocs API documentation';

    public function handle()
    {
        $this->info('Generating ByteDocs API documentation...');

        $byteDocsInstance = app('bytedocs');
        $routes = Route::getRoutes();
        
        $detectedCount = 0;
        $skippedRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            $method = $route->methods()[0] ?? 'GET';
            
            if ($this->option('debug')) {
                $this->line("Checking route: {$method} {$uri}");
            }
            
            // Skip ByteDocs routes and system routes
            if ($this->shouldSkipRoute($route)) {
                $skippedRoutes[] = "{$method} {$uri}";
                if ($this->option('debug')) {
                    $this->line("  -> Skipped");
                }
                continue;
            }

            $byteDocsInstance->addRoute($route);
            $detectedCount++;
            
            if ($this->option('debug')) {
                $this->line("  -> Added to documentation");
            }
        }

        // Generate documentation
        $byteDocsInstance->generate();

        $this->info("Documentation generated successfully!");
        $this->info("Detected {$detectedCount} API routes");
        
        if ($this->option('debug') && !empty($skippedRoutes)) {
            $this->warn("Skipped routes:");
            foreach ($skippedRoutes as $route) {
                $this->line("  - {$route}");
            }
        }
        
        $docsPath = config('bytedocs.docs_path', '/docs');
        $this->info("View documentation at: " . url($docsPath));
    }

    protected function shouldSkipRoute($route): bool
    {
        $uri = $route->uri();
        $methods = $route->methods();
        $excludePaths = config('bytedocs.exclude_paths', []);

        // Skip HEAD and OPTIONS methods
        if (in_array('HEAD', $methods) && count($methods) === 1) {
            return true;
        }

        // Skip ByteDocs routes
        $docsPath = trim(config('bytedocs.docs_path', '/docs'), '/');
        if (str_starts_with($uri, $docsPath)) {
            return true;
        }

        // Skip Laravel system routes
        $systemRoutes = ['_ignition', 'sanctum', 'api/documentation', 'telescope', 'horizon'];
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

        return false;
    }
}