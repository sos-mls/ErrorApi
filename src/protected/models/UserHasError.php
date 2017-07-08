<?php

Yii::import('application.models._base.BaseUserHasError');

class UserHasError extends BaseUserHasError
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}