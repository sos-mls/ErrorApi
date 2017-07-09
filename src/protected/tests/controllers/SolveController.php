<?php

/**
 * Contains the SolveController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

/**
 * SolveController_Test class. A PHPUnit Test case class.
 *
 * Tests that the SolveController responds according to the given error hash id.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class SolveController_Test extends TestController
{

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'SolveController';
    }

    /**
     * Contains an array of test errors that will be created.
     * 
     * @return array An array of information for Error Creation.
     */
    public function input_actionError()
    {
        return [
            [
                "Error to the Throne Information" . mt_rand(),
                0
            ],
            [
                "Trace: Error: WabaLubba Dub Dub
                    at <anonymous>:2:10\n" . 
                "Sun Du Gotta Reed Dis!" . mt_rand(),
                1
            ],
            [
                "Eruditionis habes multa temporum non uxor mea, et non vident et cyminum sparget ferreo canis exprimamus. Interdum vos iustus have ut sit ad aliquid in fornacem excitanda\n
                Eruditionis habes multa temporum non uxor mea, et non vident et cyminum sparget ferreo canis exprimamus. Interdum vos iustus have ut sit ad aliquid in fornacem excitanda\n
                Eruditionis habes multa temporum non uxor mea, et non vident et cyminum sparget ferreo canis exprimamus. Interdum vos iustus have ut sit ad aliquid in fornacem excitanda\n" . 
                "Why u Havtu be mad!" . mt_rand(),
                1
            ],
            [
                "" . mt_rand(),
                0
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
     * Tests the creation of an error and then the confirmation of its retrieval from
     * the SolveController.
     * 
     * @dataProvider input_actionError
     * 
     * @param  string  $information  The information of the Error
     * @param  integer $is_solved    If the error is solved
     */
    public function test_actionError(
        $information = "",
        $is_solved = 0
    ) {

        $error = new DBError();
        $error->error_hash_id = Yii::app()->random->hashID();
        $error->information = $information;
        $error->is_solved = $is_solved;
        $error->error_count = 0;
        $error->user_count = 0;
        $error->last_occurrance_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
        $error->created_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
        $error->save();

        $error_json = $this->getOKJSON('/solve/error/' . $error->error_hash_id, 'actionError');

        $error = DBError::model()->errorHashID($error->error_hash_id)->find();

        $this->assertEquals($error_json->is_solved, DBError::IS_SOLVED);
        $this->assertEquals($error->is_solved, DBError::IS_SOLVED);

        $error->delete();
    }

    /**
     * Tests the actionError method without a error hash id in the URL.
     */
    public function test_actionErrorNoHash()
    {
        $expectedOutput = "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Please send the error_hash_id"]}}';

        $this->assertControllerResponse('actionError', '/solve/error/', $expectedOutput);
    }

    /**
     * Tests the actionError method with a non existant error_hash_id.
     */
    public function test_actionErrorNonExistant()
    {
        $expectedOutput = "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Error not found"]}}';

        $this->assertControllerResponse('actionError', '/solve/error/abc123', $expectedOutput);
    }
}