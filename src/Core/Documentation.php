<?php

namespace ByteDocs\Laravel\Core;

class Documentation
{
    public function __construct(
        public APIInfo $info,
        public array $endpoints = [],
        public array $schemas = []
    ) {}

    public function toArray(): array
    {
        return [
            'info' => $this->info->toArray(),
            'endpoints' => array_map(fn($section) => $section->toArray(), $this->endpoints),
            'schemas' => $this->schemas,
        ];
    }
}