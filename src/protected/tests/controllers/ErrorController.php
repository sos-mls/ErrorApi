<?php

/**
 * Contains the ErrorController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

/**
 * ErrorController_Test class. A PHPUnit Test case class.
 *
 * Tests specific functions inside of the Error controller class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class ErrorController_Test extends TestController
{

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'ErrorController';
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
     * Tests the actionIndex method.
     */
    public function test_actionIndex()
    {
        $expectedOutput = "HTTP/1.1 200 OK\n" .
            "Content-type: application/json\n" .
            '{"api":{"create":"https:\/\/bitbucket.org\/scooblyboo\/errorapi\/wiki\/api\/Create","solve":"https:\/\/bitbucket.org\/scooblyboo\/errorapi\/wiki\/api\/Solve","read":"https:\/\/bitbucket.org\/scooblyboo\/errorapi\/wiki\/api\/Read"},"settings":"https:\/\/bitbucket.org\/scooblyboo\/errorapi\/wiki\/Settings","testing":"https:\/\/bitbucket.org\/scooblyboo\/errorapi\/wiki\/Testing"}';

        $this->assertControllerResponse('actionIndex', '/error/', $expectedOutput);
    }
}