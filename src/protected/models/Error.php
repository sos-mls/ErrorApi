<?php

Yii::import('application.models._base.BaseError');

class Error extends BaseError
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}