<?php

namespace mrssoft\plugins;

/**
 * Базовый класс для плагинов материалов
 */
abstract class MaterialPlugin
{
    abstract public function init(): void;

    abstract public function run(array $params = []): string;

    protected function render(string $view, array $data = []): string
    {
        $className = get_class($this);
        $class = new \ReflectionClass($className);
        $path = dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
        extract($data, EXTR_OVERWRITE);
        ob_start();
        include $path;

        return ob_get_clean();
    }
}