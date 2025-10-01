<?php

namespace ByteDocs\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use ByteDocs\Laravel\Core\APIDocs;
use ByteDocs\Laravel\Core\Config;
use ByteDocs\Laravel\Commands\GenerateDocsCommand;
use ByteDocs\Laravel\Commands\ManageBansCommand;
use ByteDocs\Laravel\Commands\InstallCommand;
use ByteDocs\Laravel\Middleware\DocsAuthMiddleware;

class ByteDocsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/bytedocs.php',
            'bytedocs'
        );

        $this->app->bind('bytedocs', function ($app) {
            $config = new Config($app['config']['bytedocs']);
            return new APIDocs($config);
        });

        $this->app->bind(APIDocs::class, function ($app) {
            $config = new Config($app['config']['bytedocs']);
            return new APIDocs($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('bytedocs.auth', DocsAuthMiddleware::class);

        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/bytedocs.php' => config_path('bytedocs.php'),
        ], 'bytedocs-config');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/bytedocs'),
        ], 'bytedocs-views');

        // Register only ByteDocs internal routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bytedocs');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateDocsCommand::class,
                ManageBansCommand::class,
                InstallCommand::class,
            ]);
        }

        // Auto-register routes for documentation if enabled
        if (config('bytedocs.auto_detect', true)) {
            $this->app->booted(function () {
                $this->autoDetectRoutes();
            });
        }
    }

    /**
     * Auto-detect Laravel routes and register them with ByteDocs
     */
    protected function autoDetectRoutes(): void
    {
        $byteDocsInstance = $this->app->make('bytedocs');
        $routes = Route::getRoutes();
        
        $detectedCount = 0;
        $skippedRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            
            // Skip ByteDocs routes and other system routes
            if ($this->shouldSkipRoute($route)) {
                $skippedRoutes[] = "{$route->methods()[0]} {$uri}";
                continue;
            }

            $byteDocsInstance->addRoute($route);
            $detectedCount++;
        }


        // Generate documentation after all routes are added
        $byteDocsInstance->generate();
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

        // Check route detection mode configuration
        if (!$this->shouldIncludeRouteByMode($route)) {
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

        // Skip Laravel internal routes (fallback route, etc.)
        if ($uri === '/' && empty($route->getName()) && in_array('GET', $methods)) {
            return true;
        }

        return false;
    }

    /**
     * Check if route should be included based on detection mode
     */
    protected function shouldIncludeRouteByMode($route): bool
    {
        $mode = config('bytedocs.route_detection.mode', 'both');
        
        if ($mode === 'both') {
            return true;
        }

        // Get route definition file by checking route action
        $action = $route->getAction();
        $routeFile = $this->getRouteFile($action, $route);
        
        // Skip routes with unknown source when mode is specific
        if ($routeFile === null) {
            return false;
        }
        
        return match ($mode) {
            'web' => $routeFile === 'web',
            'api' => $routeFile === 'api',
            default => true,
        };
    }

    /**
     * Determine which route file the route belongs to
     */
    protected function getRouteFile(array $action, $route = null): ?string
    {
        // Check if route has a file definition
        if (isset($action['file'])) {
            $file = $action['file'];
            if (str_contains($file, 'routes/web.php')) {
                return 'web';
            }
            if (str_contains($file, 'routes/api.php')) {
                return 'api';
            }
        }

        // Fallback: check URI pattern
        // API routes typically start with 'api/'
        if ($route) {
            $uri = $route->uri();
            if (str_starts_with($uri, 'api/')) {
                return 'api';
            }
        }

        // Check middleware for api routes
        if ($route && in_array('api', $route->middleware())) {
            return 'api';
        }

        // Return null if route source cannot be determined
        return null;
    }
}