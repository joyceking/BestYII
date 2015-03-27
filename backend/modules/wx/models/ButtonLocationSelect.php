<?php

namespace backend\modules\wx\models;

class ButtonLocationSelect extends \backend\modules\wx\models\Button
{
	public $type = 'location_select';
	public $key;
	
	public function __construct($name='button', $key='10') 
	{
		parent::__construct($name);
		$this->key = $key;
	}	
	
}

