<?php

namespace tests;

use mrssoft\plugins\MaterialPluginBehavior;

class MaterialPluginBehaviorTest extends \PHPUnit\Framework\TestCase
{
    public function testTextWithPlugins()
    {
        $behavior = new MaterialPluginBehavior([
            'pluginNamespace' => '\\tests\\',
            'plugins' => [
                'items' => 'TestPlugin'
            ]
        ]);

        $model = new TestActiveRecord();
        $model->text = 'TEST START {plugin:items[category=9326]} TEST END';
        $model->attachBehavior('materialPlugin', $behavior);

        self::assertEquals('TEST START category=9326 TEST END', $model->text);
    }

    public function testTextClearPlugins()
    {
        $behavior = new MaterialPluginBehavior([
            'clear' => true,
            'pluginNamespace' => '\\tests\\',
            'plugins' => [
                'items' => 'TestPlugin'
            ]
        ]);

        $model = new TestActiveRecord();
        $model->text = 'TEST START {plugin:items[category=9326]} TEST END';
        $model->attachBehavior('materialPlugin', $behavior);

        self::assertEquals('TEST START  TEST END', $model->text);
    }

    public function testNotActivePlugins()
    {
        $behavior = new MaterialPluginBehavior([
            'active' => false,
            'pluginNamespace' => '\\tests\\',
            'plugins' => [
                'items' => 'TestPlugin'
            ]
        ]);

        $model = new TestActiveRecord();
        $model->text = 'TEST START {plugin:items[category=9326]} TEST END';
        $model->attachBehavior('materialPlugin', $behavior);

        self::assertEquals('TEST START {plugin:items[category=9326]} TEST END', $model->text);
    }
}