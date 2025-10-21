<?php

namespace ByteDocs\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'bytedocs:install';
    protected $description = 'Install ByteDocs configuration files and setup environment variables';

    public function handle()
    {
        $this->info('Installing ByteDocs...');

        // Publish config file
        $this->call('vendor:publish', [
            '--tag' => 'bytedocs-config',
            '--force' => true
        ]);

        // Publish assets (CSS)
        $this->publishAssets();

        // Setup .env variables
        $this->setupEnvironmentFile();

        $this->info('✅ ByteDocs installation completed!');
        $this->line('');
        $this->info('Next steps:');
        $this->line('1. Configure your environment variables in .env');
        $this->line('2. Visit ' . config('app.url') . config('bytedocs.docs_path', '/docs') . ' to view your API documentation');
        $this->line('3. Enable AI features by setting BYTEDOCS_AI_ENABLED=true and configuring your AI provider');

        return 0;
    }

    protected function publishAssets()
    {
        $this->info('Publishing ByteDocs assets...');

        $publicPath = public_path('bytedocs');

        // Create directory if it doesn't exist
        if (!File::isDirectory($publicPath)) {
            File::makeDirectory($publicPath, 0755, true);
        }

        // Copy CSS file
        $cssSource = __DIR__ . '/../../resources/assets/bytedocs.css';
        $cssDestination = $publicPath . '/bytedocs.css';

        if (File::exists($cssSource)) {
            File::copy($cssSource, $cssDestination);
            $this->line('✓ Published: bytedocs.css');
        } else {
            $this->warn('⚠ CSS file not found at: ' . $cssSource);
        }

        // Copy JS file
        $jsSource = __DIR__ . '/../../resources/assets/bytedocs.js';
        $jsDestination = $publicPath . '/bytedocs.js';

        if (File::exists($jsSource)) {
            File::copy($jsSource, $jsDestination);
            $this->line('✓ Published: bytedocs.js');
        } else {
            $this->warn('⚠ JS file not found at: ' . $jsSource);
        }
    }

    protected function setupEnvironmentFile()
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');

        if (!File::exists($envPath)) {
            $this->warn('No .env file found. Please create one first.');
            return;
        }

        $envContent = File::get($envPath);
        $envExampleContent = File::exists($envExamplePath) ? File::get($envExamplePath) : '';

        $byteDocsVars = [
            'BYTEDOCS_TITLE' => '"API Documentation"',
            'BYTEDOCS_VERSION' => '"1.0.0"',
            'BYTEDOCS_DESCRIPTION' => '"Auto-generated API documentation with AI assistance"',
            'BYTEDOCS_PATH' => '"/docs"',
            'BYTEDOCS_AUTO_DETECT' => 'true',
            'BYTEDOCS_AUTH_ENABLED' => 'false',
            'BYTEDOCS_AI_ENABLED' => 'false',
            'BYTEDOCS_AI_PROVIDER' => '"openai"',
            'BYTEDOCS_THEME' => '"auto"',
            'BYTEDOCS_SHOW_TRY_IT' => 'true',
            'BYTEDOCS_SHOW_SCHEMAS' => 'true',
        ];

        $varsToAdd = [];
        foreach ($byteDocsVars as $key => $defaultValue) {
            if (!str_contains($envContent, $key . '=')) {
                $varsToAdd[] = $key . '=' . $defaultValue;
            }
        }

        if (!empty($varsToAdd)) {
            $this->info('Adding ByteDocs environment variables to .env...');
            
            $newContent = $envContent;
            if (!str_ends_with($envContent, "\n")) {
                $newContent .= "\n";
            }
            
            $newContent .= "\n# ByteDocs Configuration\n";
            $newContent .= implode("\n", $varsToAdd) . "\n";
            
            File::put($envPath, $newContent);
            
            foreach ($varsToAdd as $var) {
                $this->line("Added: {$var}");
            }
        } else {
            $this->info('ByteDocs environment variables already exist in .env');
        }
    }
}