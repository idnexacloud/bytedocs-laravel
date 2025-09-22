<?php

namespace ByteDocs\Laravel\Core;

class UIConfig
{
    public string $theme;
    public bool $showTryIt;
    public bool $showSchemas;
    public ?string $customCSS;
    public ?string $customJS;
    public ?string $favicon;
    public ?string $title;
    public ?string $subtitle;

    public function __construct(array $config = [])
    {
        $this->theme = $config['theme'] ?? 'auto';
        $this->showTryIt = $config['show_try_it'] ?? true;
        $this->showSchemas = $config['show_schemas'] ?? true;
        $this->customCSS = $config['custom_css'] ?? null;
        $this->customJS = $config['custom_js'] ?? null;
        $this->favicon = $config['favicon'] ?? null;
        $this->title = $config['title'] ?? null;
        $this->subtitle = $config['subtitle'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'theme' => $this->theme,
            'showTryIt' => $this->showTryIt,
            'showSchemas' => $this->showSchemas,
            'customCSS' => $this->customCSS,
            'customJS' => $this->customJS,
            'favicon' => $this->favicon,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
        ];
    }
}