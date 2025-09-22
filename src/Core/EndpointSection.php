<?php

namespace ByteDocs\Laravel\Core;

class EndpointSection
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public array $endpoints = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'endpoints' => array_map(fn($endpoint) => $endpoint->toArray(), $this->endpoints),
        ];
    }
}