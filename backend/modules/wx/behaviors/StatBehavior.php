<?php
namespace backend\modules\wx\behaviors;

use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use Yii;

use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\U;

class StatBehavior extends Behavior
{
    public function events()
    {
        return [
            Wechat::EVENT_AFTER_SUBSCRIBE => 'afterSubscribe',
        ];
    }

    public function afterSubscribe()
    {
    	U::W('....StatafterSubscribe..............');
    }

}
