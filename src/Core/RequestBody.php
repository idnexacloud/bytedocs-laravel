<?php

namespace ByteDocs\Laravel\Core;

class RequestBody
{
    public function __construct(
        public string $contentType,
        public mixed $schema,
        public mixed $example = null,
        public bool $required = false
    ) {}

    public function toArray(): array
    {
        return [
            'contentType' => $this->contentType,
            'schema' => $this->schema,
            'example' => $this->example,
            'required' => $this->required,
        ];
    }
}