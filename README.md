# ByteDocs Laravel Package

[![Latest Stable Version](https://poser.pugx.org/idnexacloud/bytedocs-laravel/v/stable)](https://packagist.org/packages/idnexacloud/bytedocs-laravel)
[![License](https://poser.pugx.org/idnexacloud/bytedocs-laravel/license)](https://packagist.org/packages/idnexacloud/bytedocs-laravel)

**ByteDocs Laravel** is a modern alternative to Swagger with better design, auto-detection, and AI integration for Laravel applications. It automatically generates beautiful API documentation from your Laravel routes with zero configuration required.

## Features

- 🚀 **Auto Route Detection** - Automatically discovers and documents all your Laravel routes
- 🎨 **Beautiful Modern UI** - Clean, responsive interface with dark mode support  
- 🤖 **AI Integration** - Built-in AI assistant to help users understand your API
- 📱 **Mobile Responsive** - Works perfectly on all device sizes
- 🔍 **Advanced Search** - Quickly find endpoints with powerful search
- 📊 **OpenAPI Compatible** - Generates standard OpenAPI 3.0 specifications
- ⚡ **Zero Configuration** - Works out of the box with sensible defaults
- 🔧 **Highly Customizable** - Configure everything to match your needs

## Installation

Install the package via Composer:

```bash
composer require idnexacloud/laravel-bytedocs
```

The package will automatically register its service provider.

## Quick Start

### 1. Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="ByteDocs\Laravel\ByteDocsServiceProvider" --tag="bytedocs-config"
```

### 2. Add to Your Routes

ByteDocs automatically detects all your routes! Just visit `/docs` to see your documentation.

### 3. Add Route Annotations (Optional)

Enhance your documentation with PHPDoc comments:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get all users with pagination support
     * @param page query integer false "Page number for pagination"
     * @param limit query integer false "Number of users per page"  
     * @param search query string false "Search term to filter users"
     */
    public function index(Request $request)
    {
        // Your implementation
    }

    /**
     * Create a new user account
     */
    public function store(Request $request)
    {
        // Your implementation
    }

    /**
     * Get user details by ID
     * @param id path integer true "User ID to retrieve"
     */
    public function show(User $user)
    {
        // Your implementation  
    }
}
```

## Configuration

### Basic Configuration

```php
// config/bytedocs.php

return [
    'title' => 'My API Documentation',
    'version' => '1.0.0', 
    'description' => 'Comprehensive API for my application',
    
    'base_urls' => [
        ['name' => 'Production', 'url' => 'https://api.myapp.com'],
        ['name' => 'Staging', 'url' => 'https://staging-api.myapp.com'],
        ['name' => 'Local', 'url' => 'http://localhost:8000'],
    ],
    
    'docs_path' => '/docs',
    'auto_detect' => true,
];
```

### AI Integration

Enable AI assistance for your API documentation:

```php
// config/bytedocs.php

'ai' => [
    'enabled' => true,
    'provider' => 'openai', // openai, gemini, openrouter, claude
    'api_key' => env('BYTEDOCS_AI_API_KEY'),
    
    'features' => [
        'chat_enabled' => true,
        'model' => 'gpt-4o-mini',
        'max_tokens' => 1000,
        'temperature' => 0.7,
    ],
],
```

Add to your `.env`:

```env
BYTEDOCS_AI_ENABLED=true
BYTEDOCS_AI_PROVIDER=openai
BYTEDOCS_AI_API_KEY=sk-your-api-key-here
```

## Supported AI Providers

### OpenAI
```php
'ai' => [
    'provider' => 'openai',
    'api_key' => env('OPENAI_API_KEY'),
    'features' => [
        'model' => 'gpt-4o-mini', // or gpt-4, gpt-3.5-turbo
    ],
]
```

### Google Gemini  
```php
'ai' => [
    'provider' => 'gemini',
    'api_key' => env('GEMINI_API_KEY'), 
    'features' => [
        'model' => 'gemini-1.5-flash', // or gemini-1.5-pro
    ],
]
```

### OpenRouter
```php
'ai' => [
    'provider' => 'openrouter',
    'api_key' => env('OPENROUTER_API_KEY'),
    'features' => [
        'model' => 'openai/gpt-oss-20b:free', // Any OpenRouter model
    ],
]
```

### Claude
```php
'ai' => [
    'provider' => 'claude', 
    'api_key' => env('ANTHROPIC_API_KEY'),
    'features' => [
        'model' => 'claude-3-sonnet-20240229',
    ],
]
```

## Advanced Usage

### Manual Route Registration

```php
use ByteDocs\Laravel\Facades\ByteDocs;
use ByteDocs\Laravel\Core\RouteInfo;
use ByteDocs\Laravel\Core\Parameter;

// In a service provider or controller
ByteDocs::addRouteInfo(new RouteInfo(
    method: 'GET',
    path: '/api/custom-endpoint',
    handler: null,
    summary: 'Custom endpoint',
    description: 'This is a manually registered endpoint',
    parameters: [
        new Parameter('id', 'path', 'integer', true, 'Record ID'),
        new Parameter('include', 'query', 'string', false, 'Related data to include'),
    ]
));
```

### Exclude Routes

```php
// config/bytedocs.php

'exclude_paths' => [
    '_ignition',
    'telescope', 
    'horizon',
    'admin/*',
    'internal/*',
],
```

### Custom UI Configuration

```php
// config/bytedocs.php

'ui' => [
    'theme' => 'auto', // light, dark, auto
    'show_try_it' => true,
    'show_schemas' => true,
    'custom_css' => '/css/custom-docs.css',
    'favicon' => '/images/api-favicon.ico',
],
```

## API Endpoints

Once installed, ByteDocs provides these endpoints:

- `GET /docs` - Main documentation interface
- `GET /docs/api-data.json` - Raw documentation data  
- `GET /docs/openapi.json` - OpenAPI 3.0 specification
- `POST /docs/chat` - AI chat endpoint (if enabled)

## Environment Variables

```env
# Basic Configuration
BYTEDOCS_TITLE="My API Documentation"
BYTEDOCS_VERSION="1.0.0"
BYTEDOCS_DESCRIPTION="Comprehensive API documentation"
BYTEDOCS_PATH="/docs"
BYTEDOCS_AUTO_DETECT=true

# Base URLs
BYTEDOCS_PRODUCTION_URL="https://api.myapp.com"  
BYTEDOCS_STAGING_URL="https://staging-api.myapp.com"
BYTEDOCS_LOCAL_URL="http://localhost:8000"

# AI Configuration
BYTEDOCS_AI_ENABLED=true
BYTEDOCS_AI_PROVIDER=openai
BYTEDOCS_AI_API_KEY=sk-your-key-here
BYTEDOCS_AI_MODEL=gpt-4o-mini
BYTEDOCS_AI_MAX_TOKENS=1000
BYTEDOCS_AI_TEMPERATURE=0.7

# UI Customization
BYTEDOCS_THEME=auto
BYTEDOCS_SHOW_TRY_IT=true
BYTEDOCS_CUSTOM_CSS=/css/docs.css
```

## Annotation Reference

Document your routes with PHPDoc comments:

```php
/**
 * Route description here
 * @param parameter_name location type required "Description"
 */
```

**Parameters:**
- `parameter_name`: Name of the parameter
- `location`: `path`, `query`, `header`, or `body`  
- `type`: `string`, `integer`, `boolean`, `array`, etc.
- `required`: `true` or `false`
- `"Description"`: Human-readable description in quotes

**Examples:**
```php
// Path parameter
@param id path integer true "User ID"

// Query parameter  
@param page query integer false "Page number"
@param search query string false "Search term"

// Header parameter
@param authorization header string true "Bearer token"
```

## Requirements

- PHP 8.0+
- Laravel 9.0+
- GuzzleHTTP 7.0+

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

- 📖 [Documentation](https://github.com/idnexacloud/bytedocs-laravel)
- 🐛 [Report Issues](https://github.com/idnexacloud/bytedocs-laravel/issues)  
- 💬 [Discussions](https://github.com/idnexacloud/bytedocs-laravel/discussions)
