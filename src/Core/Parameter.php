<?php

namespace ByteDocs\Laravel\Core;

class Parameter
{
    public function __construct(
        public string $name,
        public string $in, // "path", "query", "header", "cookie"
        public string $type,
        public bool $required,
        public string $description,
        public mixed $example = null
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'in' => $this->in,
            'type' => $this->type,
            'required' => $this->required,
            'description' => $this->description,
            'example' => $this->example,
        ];
    }
}