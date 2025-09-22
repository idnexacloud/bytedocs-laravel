<?php

namespace ByteDocs\Laravel\AI;

class AIConfig
{
    public string $provider;
    public string $apiKey;
    public bool $enabled;
    public AIFeatures $features;
    public array $settings;

    public function __construct(array $config = [])
    {
        $this->provider = $config['provider'] ?? 'openai';
        $this->apiKey = $config['api_key'] ?? '';
        $this->enabled = $config['enabled'] ?? false;
        $this->features = new AIFeatures($config['features'] ?? []);
        $this->settings = $config['settings'] ?? [];
    }

    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'apiKey' => $this->apiKey,
            'enabled' => $this->enabled,
            'features' => $this->features->toArray(),
            'settings' => $this->settings,
        ];
    }
}

class AIFeatures
{
    public bool $chatEnabled;
    public bool $docGenerationEnabled;
    public string $model;
    public int $maxTokens;
    public int $maxCompletionTokens;
    public float $temperature;

    public function __construct(array $config = [])
    {
        $this->chatEnabled = $config['chat_enabled'] ?? true;
        $this->docGenerationEnabled = $config['doc_generation_enabled'] ?? false;
        $this->model = $config['model'] ?? 'gpt-4o-mini';
        $this->maxTokens = $config['max_tokens'] ?? 1000;
        $this->maxCompletionTokens = $config['max_completion_tokens'] ?? 1000;
        $this->temperature = $config['temperature'] ?? 0.7;
    }

    public function toArray(): array
    {
        return [
            'chatEnabled' => $this->chatEnabled,
            'docGenerationEnabled' => $this->docGenerationEnabled,
            'model' => $this->model,
            'maxTokens' => $this->maxTokens,
            'maxCompletionTokens' => $this->maxCompletionTokens,
            'temperature' => $this->temperature,
        ];
    }
}

class ChatRequest
{
    public function __construct(
        public string $message,
        public string $context = '',
        public mixed $endpoint = null,
        public array $metadata = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['message'] ?? '',
            $data['context'] ?? '',
            $data['endpoint'] ?? null,
            $data['metadata'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'context' => $this->context,
            'endpoint' => $this->endpoint,
            'metadata' => $this->metadata,
        ];
    }
}

class ChatResponse
{
    public function __construct(
        public string $response = '',
        public string $provider = '',
        public string $model = '',
        public int $tokensUsed = 0,
        public string $error = ''
    ) {}

    public function toArray(): array
    {
        return [
            'response' => $this->response,
            'provider' => $this->provider,
            'model' => $this->model,
            'tokensUsed' => $this->tokensUsed,
            'error' => $this->error,
        ];
    }
}