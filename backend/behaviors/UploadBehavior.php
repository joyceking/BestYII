<?php

namespace backend\behaviors;

use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\validators\Validator;
use Yii;

use backend\modules\wx\models\U;

class UploadBehavior extends \vova07\fileapi\behaviors\UploadBehavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            self::EVENT_AFTER_UPLOAD => 'afterUpload',            
        ];
    }

    public function afterUpload()
    {
//    	U::W('afterUpload..............');
    }

}
