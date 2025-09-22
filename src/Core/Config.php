<?php

namespace ByteDocs\Laravel\Core;

use ByteDocs\Laravel\AI\AIConfig;

class Config
{
    public array $config;
    public string $title;
    public string $version;
    public string $description;
    public string $baseURL;
    public array $baseURLs;
    public string $docsPath;
    public bool $autoDetect;
    public array $excludePaths;
    public ?UIConfig $uiConfig;
    public ?AIConfig $aiConfig;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->title = $config['title'] ?? 'API Documentation';
        $this->version = $config['version'] ?? '1.0.0';
        $this->description = $config['description'] ?? 'Auto-generated API documentation';
        $this->docsPath = $config['docs_path'] ?? '/docs';
        $this->autoDetect = $config['auto_detect'] ?? true;
        $this->excludePaths = $config['exclude_paths'] ?? [];

        // Handle base URLs (backward compatibility + new format)  
        $this->baseURLs = $config['base_urls'] ?? [];
        $this->baseURL = !empty($this->baseURLs) ? $this->baseURLs[0]['url'] : (config('app.url') ?? 'http://localhost:8000');
        
        // // Initialize UI config
        $this->uiConfig = isset($config['ui']) ? new UIConfig($config['ui']) : null;

        // // Initialize AI config
        $this->aiConfig = isset($config['ai']) && $config['ai']['enabled'] 
            ? new AIConfig($config['ai']) 
            : null;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->config['title'] ?? 'API Documentation',
            'version' => $this->config['version'] ?? '1.0.0',
            'description' => $this->config['description'] ?? 'Auto-generated API documentation',
            'baseURL' => !empty($this->config['base_urls'] ?? []) 
                ? ($this->config['base_urls'][0]['url'] ?? (config('app.url') ?? 'http://localhost:8000')) 
                : (config('app.url') ?? 'http://localhost:8000'),
            'baseUrls' => $this->config['base_urls'] ?? [],
            'docsPath' => $this->config['docs_path'] ?? '/docs',
            'autoDetect' => $this->config['auto_detect'] ?? true,
            'excludePaths' => $this->config['exclude_paths'] ?? [],
            'uiConfig' => $this->uiConfig?->toArray(),
            'aiConfig' => $this->aiConfig?->toArray(),
        ];
    }
}