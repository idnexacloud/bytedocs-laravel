<?php

namespace ByteDocs\Laravel\Core;

class Response
{
    public function __construct(
        public string $description,
        public mixed $example = null,
        public mixed $schema = null
    ) {}

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'example' => $this->example,
            'schema' => $this->schema,
        ];
    }
}