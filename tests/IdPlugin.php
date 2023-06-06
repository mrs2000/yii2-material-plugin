<?php

namespace tests;

use mrssoft\plugins\MaterialPlugin;

class IdPlugin extends MaterialPlugin
{
    private int $id = 0;

    public function init(): void
    {
    }

    public function run(array $params = []): string
    {
        $this->id++;
        return $this->id;
    }
}