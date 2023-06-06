<?php

namespace tests;

use mrssoft\plugins\PluginHelper;
use mrssoft\plugins\PluginInfo;

class MaterialPluginHelperTest extends \PHPUnit\Framework\TestCase
{
    public function testParseCode(): void
    {
        $text = 'TEST START {plugin:items[category=9326,min=17100,max=193792,filter=127:3125]} TEST END';

        $result = PluginHelper::parseCode($text);

        self::assertIsArray($result);
        self::assertInstanceOf(PluginInfo::class, $result[0]);
        self::assertEquals('items', $result[0]->getName());
        self::assertEquals('{plugin:items[category=9326,min=17100,max=193792,filter=127:3125]}', $result[0]->getReplace());
        self::assertEquals(9326, $result[0]->getParams()['category']);
    }
}