<?php

/**
 * Contains the ReadController class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\ApiController;

/**
 * The ReadController Retrieves information about a given error.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class ReadController extends ApiController
{
    /**
     * Tries to get the error_hash_id from the given url request, if the error exists
     * it returns information about that error.
     *
     * @return JSON     The general error array.
     */
    public function actionError()
    {
        $hash_id = $this->getHashID('read/error');
        if ($hash_id != "") {
            try {
                if (DBError::model()->errorHashID($hash_id)->exists()) {
                    $error = DBError::model()->errorHashID($hash_id)->find();
                    $this->renderJSON($error->toArray());
                } else {
                    $this->renderJSONError("Error not found");
                }
            } catch (Exception $e) {
                $this->renderJSONError($e->getMessage(), 500);
            }
        } else {
            $this->renderJSONError("Please send the error_hash_id");
        }
    }
}
