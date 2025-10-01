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
        // Debug: Log the LLM request payload with token estimation
        $systemContext = isset($data['messages'][0]) && $data['messages'][0]['role'] === 'system' 
            ? $data['messages'][0]['content'] 
            : '';
        $userMessage = isset($data['messages']) 
            ? collect($data['messages'])->where('role', 'user')->pluck('content')->join(' | ')
            : 'no messages';
            
        // Rough token estimation (1 token ≈ 4 characters for English)
        $estimatedTokens = (strlen($systemContext) + strlen($userMessage)) / 4;
        

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'json' => $data,
                'headers' => $headers,
                'timeout' => 30,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Debug: Log the LLM response with token efficiency metrics
            $responseLength = isset($result['choices'][0]['message']['content']) 
                ? strlen($result['choices'][0]['message']['content']) 
                : 0;
            $actualTokens = $result['usage']['total_tokens'] ?? 0;
            $inputTokens = $result['usage']['prompt_tokens'] ?? 0;
            $outputTokens = $result['usage']['completion_tokens'] ?? 0;
            
            // Calculate efficiency metrics
            $compressionRatio = $estimatedTokens > 0 ? round($inputTokens / $estimatedTokens, 2) : 1;
            $outputEfficiency = $outputTokens > 0 ? round($responseLength / $outputTokens, 2) : 0;
            

            return $result;
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