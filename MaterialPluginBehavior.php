<?php

namespace mrssoft\plugins;

use Yii;
use yii\base\Behavior;

/**
 * Обрабатывает вставленные в текст плагины
 * Формат {plugin:name[param1=value1,param2=value2]}
 */
class MaterialPluginBehavior extends Behavior
{
    /**
     * Поле с исходным текстом
     */
    public string $attribute = 'text';

    /**
     * Список плагинов
     */
    public array $plugins = [];

    /**
     * Включен ли плагин
     */
    public bool $active = true;

    /**
     * Только очитска кода вставки
     */
    public bool $clear = false;

    public string $pluginNamespace = '\app\plugins\\';

    public static array $initPlugins = [];

    public function attach($owner): void
    {
        parent::attach($owner);
        $this->process($owner);
    }

    private function process($owner): void
    {
        if ($this->active && empty($owner->{$this->attribute}) === false) {
            if ($this->clear) {
                $result = $this->getTextClearPlugins($owner->{$this->attribute});
            } else {
                $result = $this->getTextWithPlugins($owner->{$this->attribute}, true);
            }

            $owner->{$this->attribute} = $result;
        }
    }

    /**
     * Текст с плагинами
     */
    public function getTextWithPlugins(string $text, bool $initPlugin = false): string
    {
        foreach (PluginHelper::parseCode($text) as $info) {
            if (array_key_exists($info->getName(), $this->plugins)) {
                $result = $this->getPluginObject($info->getName(), $initPlugin)
                               ->run($info->getParams());
            } else {
                Yii::error('Plugin not found: ' . $info->getName());
                $result = '';
            }

            $text = str_replace($info->getReplace(), $result, $text);
        }

        return $text;
    }

    /**
     * Удалить код вставки плагина из текста
     */
    public function getTextClearPlugins(string $text): string
    {
        return preg_replace('/{plugin:.*}/mU', '', $text);
    }

    private function getPluginObject(string $pluginName, bool $initPlugin): MaterialPlugin
    {
        if (array_key_exists($pluginName, self::$initPlugins) === false) {
            $class = $this->pluginNamespace . $this->plugins[$pluginName];
            $obj = new $class();
            if ($initPlugin) {
                $obj->init();
            }
            self::$initPlugins[$pluginName] = $obj;
        }

        return self::$initPlugins[$pluginName];
    }
}