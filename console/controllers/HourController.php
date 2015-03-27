<?php

/*
C:\xampp\php\php.exe C:\htdocs\yss\yii hour
php yii hour
/usr/bin/php /mnt/data0/wwwroot/yss/yii hour
0 * * * * /usr/bin/php /mnt/data0/wwwroot/yss/yii hour
*/

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\imagine\Image;

use common\models\MPhoto;

use backend\modules\wx\models\U;
use backend\modules\wx\models\Wechat;
use backend\modules\wx\models\WxException;
use backend\modules\wx\models\MAccessLog;
use backend\modules\wx\models\MAccessLogAll;
use backend\modules\wx\models\MUser;
use backend\modules\wx\models\MGh;

use common\models\MCourseSchedule;
use common\models\MCourseScheduleSignon;

class HourController extends Controller
{
	public function actionIndex()
	{
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		if (!ini_set('memory_limit', '-1'))
			U::W("ini_set(memory_limit) error");    
		$time=microtime(true);	

		U::W("###########".__CLASS__." BEGIN");		
		
		static::checkSignon();

		U::W("###########".__CLASS__." END, (time: ".sprintf('%.3f', microtime(true)-$time)."s)");			
	}

	public static function checkSignon() 
	{
		$time = date("Y-m-d H:i:s", time()-2*3600);
		//U::W("time = $time");
		foreach (MCourseScheduleSignon::find()->where(['is_signon'=>MCourseScheduleSignon::SIGNON_STATUS_NONE])->each(100) as $sigon) {
			if ($sigon->courseSchedule->start_time < $time) {
				U::W("signon_id = {$sigon->signon_id}, off");				
				$sigon->is_signon = MCourseScheduleSignon::SIGNON_STATUS_NO;
				$sigon->save();
			}
		}
		
	}
	
}

/*
		
*/		

