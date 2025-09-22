<?php

namespace ByteDocs\Laravel\Core;

class Endpoint
{
    public function __construct(
        public string $id,
        public string $method,
        public string $path,
        public string $summary,
        public string $description,
        public array $parameters = [],
        public ?RequestBody $requestBody = null,
        public array $responses = [],
        public array $tags = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'path' => $this->path,
            'summary' => $this->summary,
            'description' => $this->description,
            'parameters' => array_map(fn($param) => $param->toArray(), $this->parameters),
            'requestBody' => $this->requestBody?->toArray(),
            'responses' => array_map(fn($response) => $response->toArray(), $this->responses),
            'tags' => $this->tags,
        ];
    }
}