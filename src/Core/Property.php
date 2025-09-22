<?php

namespace ByteDocs\Laravel\Core;

class Property
{
    public function __construct(
        public string $type,
        public string $description = '',
        public mixed $example = null,
        public string $format = ''
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'description' => $this->description,
            'example' => $this->example,
            'format' => $this->format,
        ];
    }
}