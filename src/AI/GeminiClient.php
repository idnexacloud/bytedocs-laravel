<?php

namespace ByteDocs\Laravel\AI;

class GeminiClient extends Client
{
    public function chat(ChatRequest $request): array
    {
        $apiKey = $this->getApiKey();
        
        if (empty($apiKey)) {
            return $this->handleError('Gemini API key is required');
        }

        $model = $this->config->features->model;
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        
        $content = $request->message;
        
        if (!empty($request->context)) {
            $content = $request->context . "\n\nUser Question: " . $request->message;
        }

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $content]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $this->config->features->temperature,
                'maxOutputTokens' => $this->config->features->maxTokens,
            ]
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $response = $this->makeRequest($url, $data, $headers);

        if (isset($response['error'])) {
            return $this->handleError($response['error']['message'] ?? 'Gemini API error');
        }

        if (!isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            return $this->handleError('Invalid response from Gemini API');
        }

        return [
            'response' => $response['candidates'][0]['content']['parts'][0]['text'],
            'provider' => $this->getProvider(),
            'model' => $this->getModel(),
            'tokensUsed' => $response['usageMetadata']['totalTokenCount'] ?? 0,
            'error' => '',
        ];
    }

    public function getProvider(): string
    {
        return 'gemini';
    }

    public function getModel(): string
    {
        return $this->config->features->model;
    }
}