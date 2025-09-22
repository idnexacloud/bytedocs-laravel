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
        
        \Log::info('🤖 LLM Request Debug', [
            'provider' => $this->getProvider(),
            'model' => $data['model'] ?? 'unknown',
            'message_count' => count($data['messages'] ?? []),
            'system_context_length' => strlen($systemContext),
            'user_message_length' => strlen($userMessage),
            'estimated_input_tokens' => round($estimatedTokens),
            'user_message' => $userMessage
        ]);

        // Log the FULL context separately so you can see what's actually sent to LLM
        \Log::info('🤖 FULL SYSTEM CONTEXT SENT TO LLM', [
            'provider' => $this->getProvider(),
            'full_system_context' => $systemContext
        ]);

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
            
            \Log::info('🤖 LLM Response Debug', [
                'provider' => $this->getProvider(),
                'success' => !isset($result['error']),
                'response_length' => $responseLength,
                'actual_input_tokens' => $inputTokens,
                'actual_output_tokens' => $outputTokens,
                'total_tokens_used' => $actualTokens,
                'estimated_vs_actual_ratio' => $compressionRatio,
                'chars_per_output_token' => $outputEfficiency,
                'context_compression_saved' => max(0, round($estimatedTokens - $inputTokens)),
                'error' => $result['error']['message'] ?? null
            ]);

            return $result;
        } catch (\Exception $e) {
            \Log::error('🤖 LLM Request Failed', [
                'provider' => $this->getProvider(),
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            
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