<?php

/**
 * Contains the UserHasError class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

Yii::import('application.models._base.BaseUserHasError');

/**
 * The UserHasError class.
 *
 * Associates the error that have occurred with users that might have
 * received that error.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class UserHasError extends BaseUserHasError
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     *
     *
     * Scopes
     *
     *
     */

    /**
     * Filters criteria by error_id.
     *
     * @param  string $error_id The error id to filter by.
     * @return UserHasError     A reference to this.
     */
    public function errorID($error_id)
    {
        $this->getDbCriteria()->compare('t.error_id', $error_id);
        return $this;
    }

    /**
     * Filters criteria by user_id.
     *
     * @param  string $user_id The user id to filter by.
     * @return UserHasError    A reference to this.
     */
    public function userID($user_id)
    {
        $this->getDbCriteria()->compare('t.user_id', $user_id);
        return $this;
    }
}