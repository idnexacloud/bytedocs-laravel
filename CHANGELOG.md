# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2025-10-21

### Added
- **k6 Performance Testing**: Local k6 load testing integration with OS detection (Windows/macOS/Linux)
- **K6Runner Class**: New `ByteDocs\Laravel\Performance\K6Runner` for executing k6 performance tests
- **Custom k6 Path Support**: Optional custom k6 executable path with localStorage persistence
- **Multi-Stage Load Testing**: Flexible stage management with add/remove functionality for ramp-up/down patterns
- **Three-Tab Results Display**:
  - **Computer View**: Raw k6 metrics and output
  - **Analyst Result**: Automated performance grading system (A-F) with key metrics analysis
  - **AI Analyst**: LLM-powered performance analysis with actionable recommendations
- **Multi-Language AI Analysis**: Support for 10 languages (English, Indonesian, Chinese, Japanese, Korean, Spanish, French, German, Portuguese, Russian)
- **Markdown Rendering**: Safe markdown rendering using marked.js and DOMPurify for AI analysis
- **Enhanced k6 Debugging**: Detailed HTTP request/response logging with status codes and error messages
- **HTTP Traffic Detection**: Automatic validation to ensure k6 scripts actually hit endpoints
- **Performance Routes**: New endpoints for k6 test execution, script generation, system info, and AI analysis

### Enhanced
- **Error Display**: Persistent error UI with dismiss functionality instead of temporary notifications
- **Tab Styling**: Raised tab effect with clear visual indicators for active tabs
- **Table Styling**: Professional table formatting in markdown with zebra striping, hover effects, and dark mode support
- **Duration Settings**: Default changed to seconds, removed hours option for better UX
- **k6 Script Generation**: Auto-detection of HTTP methods (lowercase), automatic Content-Type headers for POST/PUT/PATCH
- **Performance Analysis Prompt**: Structured AI prompts with 5-point analysis (assessment, findings, issues, recommendations, scalability)
- **HR Spacing**: Improved horizontal rule spacing (2rem) in markdown content

### Fixed
- k6 path detection on Windows with fallback to common installation locations (Chocolatey, Scoop, WinGet)
- HTTP method case sensitivity in k6 scripts (now uses lowercase: http.get(), http.post(), etc.)
- Consistent performance analysis across all result tabs
- Tab active state visibility with background and border styling

### Technical
- Added `K6Runner` class with cross-platform support
- New validation rules for performance test configurations
- Language parameter support in AI analysis endpoint
- LocalStorage integration for user preferences (k6 path, analysis language)
- Enhanced metrics parsing with data transfer detection

## [1.1.0] - 2025-10-21

### Added
- **Automatic Response Detection**: Parse `response()->json()` from controller methods to generate accurate response examples
- **Model Structure Analysis**: Automatically extract Eloquent Model structure (fillable, casts, hidden, timestamps) for response examples
- **Smart Variable Detection**: Detect and replace variables in responses with actual Model data structures
- **Collection Detection**: Automatically detect if a variable represents a collection or single model instance
- **ModelAnalyzer**: New parser to analyze Eloquent models and generate realistic example values based on field types
- **External Assets**: Extracted inline styles and scripts to separate files (`bytedocs.css` and `bytedocs.js`) published to `public/bytedocs/`

### Enhanced
- Improved response parsing to handle multiline array syntax
- Enhanced `APIDocs` class with model-aware response generation
- Better type inference for model attributes (email, price, boolean, etc.)
- Support for chained Eloquent query methods (orderBy, where, get, etc.)
- Optimized page load by separating CSS and JavaScript from inline code
- InstallCommand now publishes both CSS and JS assets to public directory
- Better browser caching with external asset files

### Fixed
- Response tab now displays actual model structure instead of generic `{"status": "success"}`
- Regex pattern matching for multiline PHP arrays in controller methods

## [1.0.0] - 2024-09-30

### Release
First stable ByteDocs Laravel release, auto-generating polished API docs at /docs.
Built-in AI assistant with OpenAI, Gemini, OpenRouter, and Claude options, plus ban management and chat endpoint.
OpenAPI 3.1 exports in JSON/YAML alongside FormRequest parsing, PHPDoc annotations, and manual route registration.
Session-based auth, route exclusion controls, and configurable theme/UI via config or environment variables.

### Requirements
- PHP 8.0+
- Laravel 9-12

## [0.1.0] - 2025-08-31

### Added
- Auto route detection for Laravel applications
- Beautiful modern UI with dark mode support
- AI integration with multiple providers (OpenAI, Gemini, OpenRouter, Claude)
- Mobile responsive documentation interface
- Advanced search functionality for endpoints
- OpenAPI 3.0 compatible specification generation
- Zero configuration setup with sensible defaults
- PHPDoc annotation support for enhanced documentation
- Manual route registration capabilities
- Route exclusion patterns
- Custom UI theming and configuration
- Authentication middleware for docs access
- Installation and management commands
- Ban management system for AI features
- Multiple base URL environment support
- Chat interface for AI assistance
- Guzzle HTTP client integration
- Laravel service provider auto-discovery
- Comprehensive configuration options

### Features
- **Core Documentation Engine**: Automatic Laravel route discovery and parsing
- **AI Assistant**: Multi-provider AI integration for API documentation help
- **Modern UI**: Responsive design with light/dark theme support
- **Configuration**: Extensive customization options via config file and environment variables
- **Security**: Built-in authentication middleware and ban management
- **Extensibility**: Manual route registration and custom endpoint documentation

### Requirements
- PHP 8.0+
- Laravel 9.0+ (supports up to Laravel 12.0)
- GuzzleHTTP 7.0+

### Initial Release
This is the first release of ByteDocs Laravel package, providing a modern alternative to Swagger documentation with enhanced AI capabilities and beautiful design.
