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
            '{"api":{"create":"https:\/\/github.com\/sos-mls\/ErrorApi\/wiki\/API-Create","solve":"https:\/\/github.com\/sos-mls\/ErrorApi\/wiki\/API-Solve","read":"https:\/\/github.com\/sos-mls\/ErrorApi\/wiki\/API-Read"},"settings":"https:\/\/github.com\/sos-mls\/ErrorApi\/wiki\/Settings","testing":"https:\/\/github.com\/sos-mls\/ErrorApi\/wiki\/Testing"}';

        $this->assertControllerResponse('actionIndex', '/error/', $expectedOutput);
    }
}