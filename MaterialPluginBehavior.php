<?

namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Обрабатывает вставленные в текст плагины
 * Формат {plugin:name[param1=value1,param2=value2]}
 */
class MaterialPluginBehavior extends Behavior
{
    /**
     * Поле с исходным текстом
     * @var string
     */
    public $attribute = 'text';

    /**
     * Список плагинов
     * @var array
     */
    public $plugins = [];

    /**
     * Включен ли плагин
     * @var bool
     */
    public $active = true;

    /**
     * Путь к папке в плагинами
     * @var string
     */
    public $pluginNamespace = '\app\plugins\\';

    private function getCacheID()
    {
        /** @var \yii\db\ActiveRecord $owner */
        $owner = $this->owner;

        return 'material-plugin-' . get_class($owner) . '-' . $owner->primaryKey;
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    public function afterFind()
    {
        if ($this->active && !empty($this->owner->{$this->attribute})) {
            if (empty($result)) {
                $result = $this->getTextWithPlugins($this->owner->{$this->attribute}, true);
            }

            $this->owner->{$this->attribute} = $result;
        }
    }

    public function afterSave()
    {
        $cacheID = $this->getCacheID();
        Yii::$app->cache->delete($cacheID);
    }

    /**
     * Текст с плагинами
     * @param string $text
     * @param $initPlugin
     * @return string
     */
    public function getTextWithPlugins($text, $initPlugin = false)
    {
        if (preg_match_all('/{plugin:(.+)(\[(.+)\])?}/mU', $text, $matches)) {
            foreach ($matches[1] as $n => $plugin) {
                if (array_key_exists($plugin, $this->plugins)) {
                    $params = [];
                    foreach (explode(',', $matches[3][$n]) as $p) {
                        $t = explode('=', $p);
                        $params[$t[0]] = $t[1];
                    }
                    $pluginResult = $this->getPluginObject($plugin, $initPlugin)
                                         ->run($params);
                } else {
                    Yii::error('Plugin not found: ' . $plugin);
                    $pluginResult = '';
                }

                $text = str_replace($matches[0][$n], $pluginResult, $text);
            }
        }

        return $text;
    }

    /**
     * Удалить код вставки плагина из текста
     * @param string $text
     * @return string
     */
    public function getTextClearPlugins($text)
    {
        return preg_replace('/{plugin:.*}/mU', '', $text);
    }

    /**
     * @param $pluginName
     * @param bool $initPlugin
     * @return MaterialPlugin
     */
    private function getPluginObject($pluginName, $initPlugin)
    {
        /** @var $obj MaterialPlugin */

        if (isset(Yii::$app->params['material-plugins'][$pluginName])) {
            $obj = Yii::$app->params['material-plugins'][$pluginName];
        } else {
            $class = $this->pluginNamespace . $this->plugins[$pluginName];
            $obj = new $class();
            if ($initPlugin) {
                $obj->init();
            }

            Yii::$app->params['material-plugins'][$pluginName] = $obj;
        }

        return $obj;
    }
}