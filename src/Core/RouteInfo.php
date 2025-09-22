<?php

namespace ByteDocs\Laravel\Core;

class RouteInfo
{
    public function __construct(
        public string $method,
        public string $path,
        public mixed $handler,
        public array $middlewares = [],
        public string $summary = '',
        public string $description = '',
        public array $parameters = []
    ) {}

    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'path' => $this->path,
            'summary' => $this->summary,
            'description' => $this->description,
            'parameters' => array_map(fn($param) => $param->toArray(), $this->parameters),
        ];
    }
}