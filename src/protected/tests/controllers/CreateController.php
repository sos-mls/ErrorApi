<?php

/**
 * Contains the CreateController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

/**
 * CreateController_Test class. A PHPUnit Test case class.
 *
 * Tests the CreateController ensureing that errors are creating and stored properly.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class CreateController_Test extends TestController
{

    const EMPTY_STRING = "";

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'CreateController';
    }

    /**
     *
     *
     *
     * Test
     *
     *
     *
     */

    /**
     * Contains an array of test files for deleting.
     * 
     * @return array An array of information for Error Creation.
     */
    public function input_actionError()
    {
        return [
            [
                "Error Information",
                "email_address",
                "user_hash_id"
            ],
            [
                "Trace: Error: WabaLubba Dub Dub
                    at <anonymous>:2:10\n",
                "christian.micklisch@successwithsos.com",
                "deafult_hash_id"
            ],
            [
                "Error Information",
                "",
                ""
            ]
        ];
    }

    /**
     *
     *
     *
     * Test
     *
     *
     *
     */

    /**
     * Tests the actionCreate method.
     * 
     * @dataProvider input_actionError
     * 
     * @param  string $information   The information of the Error
     * @param  string $email_address Whom to email when an error has been created.
     * @param  string $user_hash_id  The user that received the given error.
     */
    public function test_actionError(
        $information = "", 
        $email_address = "", 
        $user_hash_id = ""
    ) {
        $_POST = [
            'information'   => $information,
            'email_address' => $email_address,
            'user_hash_id'  => $user_hash_id
        ];

        $error_json = $this->getOKJSON('/create/error', 'actionError');

        $this->assertTrue(DBError::model()->errorHashId($error_json->error_hash_id)->exists());
        if (DBError::model()->errorHashID($error_json->error_hash_id)->exists()) {
            $this->assertCreationEquals(DBError::model()->errorHashID($error_json->error_hash_id)->find());
        }
    }

    /**
     * Simply does not set a POST to cause a dependency error on the creation 
     * of an error.
     */
    public function test_actionErrorNoPOST() 
    {
        $_POST = null;

        $expected_output = "HTTP/1.1 424 \n" .
            "Content-type: application/json\n" .
            '{"errors":{"general":["Not a proper http method type, please send a POST"]}}';

        $this->assertControllerResponse('actionError',  '/create/error', $expected_output);
    }

    /**
     * Simply does not set a POST to cause a dependency error on the creation 
     * of an error.
     */
    public function test_actionErrorNoInformation() 
    {
        $_POST = [
            'information' => ''
        ];

        $expected_output = "HTTP/1.1 424 \n" .
            "Content-type: application/json\n" .
            '{"errors":{"general":["Error cannot be created as no information was provided, please send a POST with \'information\'"]}}';

        $this->assertControllerResponse('actionError',  '/create/error', $expected_output);
    }

    /**
     * Ensures that the error that was given is properly logged with the current
     * POST values of the information along with the user tracked.
     * 
     * @param  DBError  $error The error that was recently created.
     */
    private function assertCreationEquals(DBError $error) 
    {
        $this->assertEquals($error->information, $_POST['information'], "The information of the error given is not equal to the error that was created.");

        if ($_POST['user_hash_id'] == self::EMPTY_STRING) {
            return;
        }

        $user_is_logged = false;
        foreach ($error->userHasErrors as $user_hash_error) {
            if ($_POST['user_hash_id'] == $user_hash_error->user->user_hash_id) {
                $user_is_logged = true;
            }
        }

        $this->assertTrue($user_is_logged, "The user in the POST was not logged in the error");
    }
}