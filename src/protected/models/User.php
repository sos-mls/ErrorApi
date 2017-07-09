<?php

/**
 * Contains the User class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

Yii::import('application.models._base.BaseUser');

/**
 * The User class.
 *
 * Tracks the user_hash_id, this allows a user to be uniquely logged.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class User extends BaseUser
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
     * Filters criteria by user_hash_id.
     *
     * @param  string $user_hash_id The user hash id to filter by.
     * @return User                 A reference to this.
     */
    public function userHashID($user_hash_id)
    {
        $this->getDbCriteria()->compare('t.user_hash_id', $user_hash_id);
        return $this;
    }
}