<?php

namespace tests;

use mrssoft\plugins\MaterialPlugin;

class TestPlugin extends MaterialPlugin
{
    public function init(): void
    {
    }

    public function run(array $params = []): string
    {
        $result = '';
        foreach ($params as $key => $value) {
            $result .= "$key=$value";
        }
        return $result;
    }
}