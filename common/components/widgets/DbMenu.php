<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/5/14
 * Time: 11:16 AM
 */

namespace common\components\widgets;

use \common\models\WidgetMenu;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\caching\TagDependency;

/**
 * Class DbMenu
 * Usage:
 * echo common\components\widgets\DbMenu::widget([
 *      'key'=>'stored-menu-key',
 *      ... other options from \yii\widgets\Menu
 * ])
 * @package common\components\widgets\menu
 */
class DbMenu extends \yii\widgets\Menu{

    /**
     * @var string Key to find menu model
     */
    public $key;

    public function init()
    {
        $cacheKey = [
            WidgetMenu::className(),
            $this->key
        ];
        $this->items = \Yii::$app->cache->get($cacheKey);
        if($this->items == false){
            if(!($model = WidgetMenu::findOne(['key'=>$this->key, 'status'=>WidgetMenu::STATUS_ACTIVE]))){
                throw new InvalidConfigException;
            }
            $this->items =json_decode($model->items, true);
            \Yii::$app->cache->set($cacheKey, 60*60*24*365);
        }
    }
}