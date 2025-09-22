<?php

namespace ByteDocs\Laravel\Core;

class APIInfo
{
    public function __construct(
        public string $title,
        public string $version,
        public string $description,
        public string $baseUrl = ''
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'version' => $this->version,
            'description' => $this->description,
            'baseUrl' => $this->baseUrl,
        ];
    }
}