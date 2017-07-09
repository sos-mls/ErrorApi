<?php

/**
 * Contains the SolveController class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\ApiController;

/**
 * The SolveController lays a error to rest.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class SolveController extends ApiController
{
    /**
     * Tries to get the error_hash_id from the given url request, if the error exists
     * it returns information about that error.
     *
     * @return JSON The general error array.
     */
    public function actionError()
    {
        $hash_id = $this->getHashID('solve/error');
        if ($hash_id != "") {
            try {
                if (DBError::model()->errorHashID($hash_id)->exists()) {
                    $error = DBError::model()->errorHashID($hash_id)->find();
                    $error->is_solved = DBError::IS_SOLVED;
                    $error->save();
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
