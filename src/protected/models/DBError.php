<?php

/**
 * Contains the DBError class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

Yii::import('application.models._base.BaseError');

/**
 * The DBError class.
 *
 * Tracks am error that has occured along with the user that it has occured with.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class DBError extends BaseError
{
    const IS_NOT_SOLVED = 0;
    const IS_SOLVED = 1;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    /**
     *
     *
     * Object Methods
     *
     *
     */

    /**
     * Gets the URL to the error.
     *
     * @return string The error URL.
     */
    public function getURL()
    {
        return Yii::app()->params->relative_error_dir . $this->error_hash_id;
    }

    /**
     * Gets all of the users that are currently assocaited with the error.
     * 
     * @return array All of the user_hash_id's that are with the current error.
     */
    public function getUsers() 
    {
        $users = [];

        foreach ($this->userHasErrors as $user_hash_error) {
            $users[] = $user_hash_error->user->user_hash_id;
        }

        return $users;
    }

    /**
     * Converts all of the error information to an array.
     *
     * The error contains not only information about its "self" but also all of the
     * users assocaited with it.
     *
     * @return array All of the error information.
     */
    public function toArray()
    {
        $user_array = $this->getUsers();

        return [
            'public_url'         => $this->getURL(),
            'error_hash_id'      => $this->error_hash_id,
            'information'        => $this->information,
            'is_solved'          => $this->is_solved,
            'error_count'        => $this->error_count,
            'last_occurrance_at' => $this->last_occurrance_at,
            'created_at'         => $this->created_at,
            'users'              => $user_array
        ];
    }

    /**
     *
     *
     * Scopes
     *
     *
     */

    /**
     * Filters criteria by error_hash_id.
     *
     * @param  string $error_hash_id The error hash id to filter by.
     * @return DBError               A reference to this.
     */
    public function errorHashID($error_hash_id)
    {
        $this->getDbCriteria()->compare('t.error_hash_id', $error_hash_id);
        return $this;
    }

    /**
     * Filters criteria by information.
     *
     * @param  string $information The information to filter by.
     * @return DBError             A reference to this.
     */
    public function information($information)
    {
        $this->getDbCriteria()->compare('t.information', $information);
        return $this;
    }
}