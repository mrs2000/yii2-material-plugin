<?

namespace mrssoft\plugins;

/**
 * Базовый класс для плагинов материалов
 */
abstract class MaterialPlugin
{
    abstract public function init();

    abstract public function run(array $params = []);

    protected function render($view, array $data = [])
    {
        $className = get_class($this);
        $class = new \ReflectionClass($className);
        $path = dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
        extract($data);
        ob_start();
        include $path;

        return ob_get_clean();
    }
}