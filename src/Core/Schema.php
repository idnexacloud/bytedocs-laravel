<?php

namespace ByteDocs\Laravel\Core;

class Schema
{
    public function __construct(
        public string $type,
        public array $properties = [],
        public array $required = [],
        public mixed $example = null
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'properties' => $this->properties,
            'required' => $this->required,
            'example' => $this->example,
        ];
    }
}