<?php

namespace ByteDocs\Laravel\AI;

abstract class Client
{
    protected AIConfig $config;

    public function __construct(AIConfig $config)
    {
        $this->config = $config;
    }

    abstract public function chat(ChatRequest $request): array;
    abstract public function getProvider(): string;
    abstract public function getModel(): string;

    /**
     * Factory method to create appropriate AI client based on provider
     */
    public static function create(AIConfig $config): ?Client
    {
        if (!$config->enabled) {
            return null;
        }

        return match ($config->provider) {
            'openai' => new OpenAIClient($config),
            'gemini' => new GeminiClient($config),
            'openrouter' => new OpenRouterClient($config),
            'claude' => new ClaudeClient($config),
            default => null,
        };
    }

    /**
     * Get API key from config or environment
     */
    protected function getApiKey(): string
    {
        if (!empty($this->config->apiKey)) {
            return $this->config->apiKey;
        }

        // Try to get from environment based on provider
        return match ($this->config->provider) {
            'openai' => env('OPENAI_API_KEY', ''),
            'gemini' => env('GEMINI_API_KEY', ''),
            'openrouter' => env('OPENROUTER_API_KEY', ''),
            'claude' => env('ANTHROPIC_API_KEY', ''),
            default => '',
        };
    }

    /**
     * Make HTTP request to AI provider
     */
    protected function makeRequest(string $url, array $data, array $headers = []): array
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'json' => $data,
                'headers' => $headers,
                'timeout' => 30,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'provider' => $this->getProvider(),
            ];
        }
    }

    /**
     * Handle common error response
     */
    protected function handleError(string $error): array
    {
        return [
            'error' => $error,
            'provider' => $this->getProvider(),
            'model' => $this->getModel(),
            'tokensUsed' => 0,
            'response' => '',
        ];
    }
}