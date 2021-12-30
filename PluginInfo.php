<?php

namespace mrssoft\plugins;

class PluginInfo
{
    private string $name;

    private string $replace;

    private array $params;

    public function __construct(string $name, string $replace) {
        $this->name = $name;
        $this->replace = $replace;
    }

    public function addParams(string $key, string $value): void
    {
        $this->params[$key] = $value;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReplace(): string
    {
        return $this->replace;
    }
}