<?php

namespace ByteDocs\Laravel\AI;

class ClaudeClient extends Client
{
    public function chat(ChatRequest $request): array
    {
        $apiKey = $this->getApiKey();
        
        if (empty($apiKey)) {
            return $this->handleError('Claude API key is required');
        }

        $url = 'https://api.anthropic.com/v1/messages';
        
        $messages = [];
        
        if (!empty($request->context)) {
            $messages[] = [
                'role' => 'user',
                'content' => $request->context . "\n\nUser Question: " . $request->message
            ];
        } else {
            $messages[] = [
                'role' => 'user',
                'content' => $request->message
            ];
        }

        $data = [
            'model' => $this->config->features->model,
            'max_tokens' => $this->config->features->maxTokens,
            'messages' => $messages,
            'temperature' => $this->config->features->temperature,
        ];

        $headers = [
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ];

        $response = $this->makeRequest($url, $data, $headers);

        if (isset($response['error'])) {
            return $this->handleError($response['error']['message'] ?? 'Claude API error');
        }

        if (!isset($response['content'][0]['text'])) {
            return $this->handleError('Invalid response from Claude API');
        }

        return [
            'response' => $response['content'][0]['text'],
            'provider' => $this->getProvider(),
            'model' => $this->getModel(),
            'tokensUsed' => $response['usage']['output_tokens'] ?? 0,
            'error' => '',
        ];
    }

    public function getProvider(): string
    {
        return 'claude';
    }

    public function getModel(): string
    {
        return $this->config->features->model;
    }
}