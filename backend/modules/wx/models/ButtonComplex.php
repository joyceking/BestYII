<?php

namespace backend\modules\wx\models;

class ButtonComplex extends \backend\modules\wx\models\Button
{
	public $sub_button = [];

	public function __construct($name='button', $sub_button=[]) 
	{
		parent::__construct($name);
		$this->sub_button = $sub_button;
	}	
	
}

