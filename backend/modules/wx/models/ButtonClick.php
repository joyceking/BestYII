<?php

namespace backend\modules\wx\models;

class ButtonClick extends \backend\modules\wx\models\Button
{
	public $type = 'click';
	public $key;
	
	public function __construct($name='button', $key='10') 
	{
		parent::__construct($name);
		$this->key = $key;
	}	
	
}

