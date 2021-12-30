<?php

namespace tests;

use yii\db\ActiveRecord;

/**
 * @property-read string $textWithPlugins
 */
class TestActiveRecord extends ActiveRecord
{
    public string $text;
}