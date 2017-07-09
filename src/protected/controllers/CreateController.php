<?php

/**
 * Contains the CreateController class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\ApiController;
use SendGrid_Restful\SendGrid;

/**
 * The CreateController Saves the given error information and responds accordingly.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class CreateController extends ApiController
{
    const INFORMATION_POST_KEY = 'information';
    const EMAIL_ADDRESS_POST_KEY = 'email_address';
    const USER_HASH_ID_POST_KEY = 'user_hash_id';

    /**
     * Saves the given information into an error, if the post gives information then
     * it goes to save that error. When a user is included it is added to the users list,
     * and when an email is included then it is sent to that email.
     *
     * @return JSON     The general error array.
     */
    public function actionError()
    {
        if (empty($_POST)) {
            $this->renderJSONError("Not a proper http method type, please send a POST");
        } else if (!array_key_exists(self::INFORMATION_POST_KEY, $_POST) ||
            empty($_POST[self::INFORMATION_POST_KEY])) {
            $this->renderJSONError("Error cannot be created as no " .
                self::INFORMATION_POST_KEY .
                " was provided, please send a POST with '" .
                self::INFORMATION_POST_KEY . "'");
        } else {
            try {
                $error = $this->createError();

                if (array_key_exists(self::USER_HASH_ID_POST_KEY, $_POST) ||
                    !empty($_POST[self::USER_HASH_ID_POST_KEY])) {
                    $this->addUserToError($error, $_POST[self::USER_HASH_ID_POST_KEY]);
                }

                if (array_key_exists(self::EMAIL_ADDRESS_POST_KEY, $_POST) ||
                    !empty($_POST[self::EMAIL_ADDRESS_POST_KEY])) {
                    $this->sendEmail($error, $_POST[self::EMAIL_ADDRESS_POST_KEY]);
                }

                if (sizeof($error->getErrors()) == 0) {
                    $this->renderJSON($error->toArray());
                } else {
                    $this->renderJSONError($error->getErrors());
                }
            } catch (Exception $e) {
                $this->renderJSONError($e->getMessage(), 500);
            }
        }
    }

    /**
     * Goes to create an error from the current information.
     *
     * Creates an error from the given information and adds the current error_count value
     * by one, and tracks the creation and occurance date. If the error already exists then
     * it simply updates the last_occurance_date, the error_count, and sets the is_solved
     * value to IS_NOT_SOLVED.
     *
     * @return Asset The error created from the file given.
     */
    private function createError()
    {
        $error = null;
        if (DBError::model()->information($_POST[self::INFORMATION_POST_KEY])->exists()) {
            $error = DBError::model()->information($_POST[self::INFORMATION_POST_KEY])->find();
            $error->is_solved = DBError::IS_NOT_SOLVED;
            $error->error_count = $error->error_count + 1;
            $error->last_occurrance_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
            $error->save();
        } else {
            $error = new DBError();
            $error->error_hash_id = Yii::app()->random->hashID();
            $error->information = $_POST['information'];
            $error->is_solved = DBError::IS_NOT_SOLVED;
            $error->error_count = 1;
            $error->last_occurrance_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
            $error->created_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
            $error->save();
        }

        return $error;
    }

    /**
     * Adds a given user to the given error.
     *
     * If the user already exists in the system and is already logged with the given
     * error then it will not be tracked. Otherwise it will create a User object and
     * UserHasError object as necessary.
     *
     * @param DBError $error        The error that occured.
     * @param string  $user_hash_id The user hash id to attach to the error.
     */
    private function addUserToError(DBError $error, $user_hash_id = "")
    {
        $user = null;
        if (User::model()->userHashID($user_hash_id)->exists()) {
            $user = User::model()->userHashID($user_hash_id)->find();
        } else {
            $user = new User();
            $user->user_hash_id = $user_hash_id;
            $user->save();
        }

        if (!UserHasError::model()->userID($user->user_id)->errorID($error->error_id)->exists()) {
            $user_has_error = new UserHasError();
            $user_has_error->user_id = $user->user_id;
            $user_has_error->error_id = $error->error_id;
            $user_has_error->save();

            $error->user_count = UserHasError::model()->errorID($error->error_id)->count();
            $error->save();
        }
    }

    /**
     * Sends an email relating to the error that has occured.
     *
     * If the user already exists in the system and is already logged with the given
     * error then it will not be tracked. Otherwise it will create a User object and
     * UserHasError object as necessary.
     *
     * @param DBError $error        The error that occured.
     * @param string  $user_hash_id The user hash id to attach to the error.
     */
    private function sendEmail(DBError $error, $email_address = "")
    {
        if (!isset($error->last_email_at) ||
            strtotime($error->last_email_at) < time() - Yii::app()->params->minimum_eternal_email_execution_time) {
            $error_message = $error->information;

            if (strlen($error->information) > Yii::app()->params->max_error_email_length) {
                $error_message = substr($error_message, 0, Yii::app()->params->max_error_email_length) . "...";
            }

            SendGrid::send(
                $email_address,
                "Error Occured",
                [
                    SendGrid::TEXT => ['Error: ' . $error_message],
                    SendGrid::PREHEADER => ['An error has occured.'],
                    SendGrid::BUTTON_TEXT => ['VIEW JSON'],
                    SendGrid::BUTTON_LINK => [$error->getURL()]
                ]
            );

            $error->last_email_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
            $error->save();
        }
    }
}
