<?php

namespace backend\modules\wx\models;

class ButtonView extends \backend\modules\wx\models\Button
{
	public $type = 'view';
	public $url;

	public function __construct($name='button', $url='') 
	{
		parent::__construct($name);
		$this->url = $url;
	}	
}

