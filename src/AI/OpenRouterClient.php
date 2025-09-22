<?php

namespace ByteDocs\Laravel\AI;

class OpenRouterClient extends Client
{
    public function chat(ChatRequest $request): array
    {
        $apiKey = $this->getApiKey();
        
        if (empty($apiKey)) {
            return $this->handleError('OpenRouter API key is required');
        }

        $url = 'https://openrouter.ai/api/v1/chat/completions';
        
        $messages = [];
        
        if (!empty($request->context)) {
            $messages[] = [
                'role' => 'system',
                'content' => $request->context
            ];
        }
        
        $messages[] = [
            'role' => 'user',
            'content' => $request->message
        ];

        $data = [
            'model' => $this->config->features->model,
            'messages' => $messages,
            'temperature' => $this->config->features->temperature,
        ];

        // Use max_completion_tokens for newer models, fallback to max_tokens
        if ($this->config->features->maxCompletionTokens > 0) {
            $data['max_completion_tokens'] = $this->config->features->maxCompletionTokens;
        } else {
            $data['max_tokens'] = $this->config->features->maxTokens;
        }

        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => $this->config->settings['app_url'] ?? config('app.url', 'http://localhost:8000'),
            'X-Title' => $this->config->settings['app_name'] ?? config('app.name', 'Laravel API'),
        ];

        $response = $this->makeRequest($url, $data, $headers);

        if (isset($response['error'])) {
            return $this->handleError($response['error']['message'] ?? 'OpenRouter API error');
        }

        if (!isset($response['choices'][0]['message']['content'])) {
            return $this->handleError('Invalid response from OpenRouter API');
        }

        return [
            'response' => $response['choices'][0]['message']['content'],
            'provider' => $this->getProvider(),
            'model' => $this->getModel(),
            'tokensUsed' => $response['usage']['total_tokens'] ?? 0,
            'error' => '',
        ];
    }

    public function getProvider(): string
    {
        return 'openrouter';
    }

    public function getModel(): string
    {
        return $this->config->features->model;
    }
}