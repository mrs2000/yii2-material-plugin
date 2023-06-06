<?php

namespace tests;

use mrssoft\plugins\MaterialPluginBehavior;

class MaterialPluginBehaviorTest extends \PHPUnit\Framework\TestCase
{
    public function testTextWithPlugins(): void
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

    public function testPluginWhioutParametrs(): void
    {
        $behavior = new MaterialPluginBehavior([
            'pluginNamespace' => '\\tests\\',
            'plugins' => [
                'items' => 'TestPlugin'
            ]
        ]);

        $model = new TestActiveRecord();
        $model->text = 'TEST START {plugin:items} TEST END';
        $model->attachBehavior('materialPlugin', $behavior);

        self::assertEquals('TEST START  TEST END', $model->text);
    }

    public function testOnlyOneReplacement(): void
    {
        $behavior = new MaterialPluginBehavior([
            'pluginNamespace' => '\\tests\\',
            'plugins' => [
                'id' => 'IdPlugin'
            ]
        ]);

        $model = new TestActiveRecord();
        $model->text = 'TEST START {plugin:id} ANOTHER {plugin:id} TEST END';
        $model->attachBehavior('materialPlugin', $behavior);

        self::assertEquals('TEST START 1 ANOTHER 2 TEST END', $model->text);
    }

    public function testTextClearPlugins(): void
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

    public function testNotActivePlugins(): void
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