<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ByteDocs Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for ByteDocs API documentation
    | generator. ByteDocs provides a modern alternative to Swagger with
    | better design, auto-detection, and AI integration.
    |
    */

    /**
     * API Information
     */
    'title' => env('BYTEDOCS_TITLE', 'API Documentation'),
    'version' => env('BYTEDOCS_VERSION', '1.0.0'),
    'description' => env('BYTEDOCS_DESCRIPTION', 'Auto-generated API documentation with AI assistance'),

    /**
     * Base URLs for different environments
     * You can specify multiple environments for easy switching
     */
    'base_urls' => [
        [
            'name' => 'Production',
            'url' => env('BYTEDOCS_PRODUCTION_URL', 'https://api.yourapp.com'),
        ],
        [
            'name' => 'Staging',
            'url' => env('BYTEDOCS_STAGING_URL', 'https://staging-api.yourapp.com'),
        ],
        [
            'name' => 'Local',
            'url' => env('BYTEDOCS_LOCAL_URL', 'http://localhost:8000'),
        ],
    ],

    /**
     * Documentation path where the docs will be served
     */
    'docs_path' => env('BYTEDOCS_PATH', '/docs'),

    /**
     * Authentication Configuration
     */
    'auth' => [
        /**
         * Enable authentication for documentation access
         */
        'enabled' => env('BYTEDOCS_AUTH_ENABLED', false),

        /**
         * Password to access documentation
         */
        'password' => env('BYTEDOCS_AUTH_PASSWORD'),

        /**
         * Session expiration time in minutes
         */
        'session_expire' => (int) env('BYTEDOCS_AUTH_SESSION_EXPIRE', 1440), // 24 hours

        /**
         * IP banning configuration
         */
        'ip_ban' => [
            /**
             * Enable IP banning after failed attempts
             */
            'enabled' => env('BYTEDOCS_AUTH_IP_BAN_ENABLED', true),

            /**
             * Maximum failed attempts before IP ban
             */
            'max_attempts' => (int) env('BYTEDOCS_AUTH_IP_BAN_MAX_ATTEMPTS', 5),

            /**
             * Ban duration in minutes
             */
            'ban_duration' => (int) env('BYTEDOCS_AUTH_IP_BAN_DURATION', 60), // 1 hour
        ],

        /**
         * Admin management configuration
         */
        'admin' => [
            /**
             * Whitelisted IPs that cannot be banned (prevent admin lockout)
             * Comma separated list: 127.0.0.1,192.168.1.100
             */
            'whitelist_ips' => array_filter(array_map('trim', explode(',', env('BYTEDOCS_ADMIN_WHITELIST_IPS', '127.0.0.1')))),
        ],
    ],

    /**
     * Auto-detect routes from your Laravel application
     */
    'auto_detect' => env('BYTEDOCS_AUTO_DETECT', true),

    /**
     * Route detection configuration
     * Specify which route files to detect: 'web', 'api', or 'both'
     */
    'route_detection' => [
        /**
         * Detection mode: 'web', 'api', 'both'
         * - 'web': Only detect routes from web.php
         * - 'api': Only detect routes from api.php  
         * - 'both': Detect from both web.php and api.php
         */
        'mode' => env('BYTEDOCS_ROUTE_MODE', 'api'),
    ],

    /**
     * Paths to exclude from documentation
     */
    'exclude_paths' => [
        '_ignition',
        'telescope',
        'horizon',
        'sanctum',
        'api/documentation',
    ],

    /**
     * AI Configuration for intelligent API assistance
     */
    'ai' => [
        /**
         * Enable AI features (chat, documentation generation)
         */
        'enabled' => env('BYTEDOCS_AI_ENABLED', false),

        /**
         * AI Provider - supported: openai, gemini, openrouter, claude
         */
        'provider' => env('BYTEDOCS_AI_PROVIDER', 'openai'),

        /**
         * API Key for the chosen provider
         */
        'api_key' => env('BYTEDOCS_AI_API_KEY'),

        /**
         * AI Features configuration
         */
        'features' => [
            'chat_enabled' => env('BYTEDOCS_AI_CHAT_ENABLED', true),
            'doc_generation_enabled' => env('BYTEDOCS_AI_DOC_GENERATION_ENABLED', false),
            'model' => env('BYTEDOCS_AI_MODEL', 'gpt-4o-mini'),
            'max_tokens' => (int) env('BYTEDOCS_AI_MAX_TOKENS', 1000),
            'max_completion_tokens' => (int) env('BYTEDOCS_AI_MAX_COMPLETION_TOKENS', 1000),
            'temperature' => (float) env('BYTEDOCS_AI_TEMPERATURE', 0.7),
        ],

        /**
         * Provider-specific settings
         */
        'settings' => [
            // OpenRouter specific settings
            'app_name' => env('APP_NAME', 'Laravel API'),
            'app_url' => env('APP_URL', 'http://localhost:8000'),
        ],
    ],

    /**
     * UI Configuration
     */
    'ui' => [
        'theme' => env('BYTEDOCS_THEME', 'auto'), // light, dark, auto
        'show_try_it' => env('BYTEDOCS_SHOW_TRY_IT', true),
        'show_schemas' => env('BYTEDOCS_SHOW_SCHEMAS', true),
        'custom_css' => env('BYTEDOCS_CUSTOM_CSS'),
        'custom_js' => env('BYTEDOCS_CUSTOM_JS'),
        'favicon' => env('BYTEDOCS_FAVICON'),
        'title' => env('BYTEDOCS_UI_TITLE'),
        'subtitle' => env('BYTEDOCS_UI_SUBTITLE'),
    ],

    /**
     * Example AI Configuration Templates
     * Uncomment and modify as needed
     */

    /*
    // OpenAI Configuration Example
    'ai' => [
        'enabled' => true,
        'provider' => 'openai',
        'api_key' => 'sk-proj-your-openai-key',
        'features' => [
            'chat_enabled' => true,
            'doc_generation_enabled' => false,
            'model' => 'gpt-4o-mini',
            'max_completion_tokens' => 1500,
            'temperature' => 0.7,
        ],
    ],
    */

    /*
    // Google Gemini Configuration Example
    'ai' => [
        'enabled' => true,
        'provider' => 'gemini',
        'api_key' => 'your-gemini-api-key',
        'features' => [
            'chat_enabled' => true,
            'model' => 'gemini-1.5-flash',
            'max_tokens' => 2000,
            'temperature' => 0.7,
        ],
    ],
    */

    /*
    // OpenRouter Configuration Example
    'ai' => [
        'enabled' => true,
        'provider' => 'openrouter',
        'api_key' => 'sk-or-v1-your-openrouter-key',
        'features' => [
            'chat_enabled' => true,
            'model' => 'openai/gpt-oss-20b:free',
            'max_completion_tokens' => 1000,
            'temperature' => 0.7,
        ],
        'settings' => [
            'app_name' => 'My Laravel API',
            'app_url' => 'https://myapp.com',
        ],
    ],
    */

    /*
    // Claude Configuration Example
    'ai' => [
        'enabled' => true,
        'provider' => 'claude',
        'api_key' => 'sk-ant-your-claude-key',
        'features' => [
            'chat_enabled' => true,
            'model' => 'claude-3-sonnet-20240229',
            'max_tokens' => 1500,
            'temperature' => 0.7,
        ],
    ],
    */
];