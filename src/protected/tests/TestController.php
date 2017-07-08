<?php

/**
 * Contains the TestController class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\Reflection;

/**
 * TestController class. A PHPUnit Test case class.
 *
 * Contains helper methods for testing the controllers.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class TestController extends CDbTestCase
{
    protected $controller_name;

    /**
     * Asserts the JSON output of the current controller.
     *
     * Sets the current controller class output by setting the server's redirect url,
     * calling the method, and comparing the output of the method with the expected
     * string.
     *
     * @param  string $method          The action to call in the controller.
     * @param  string $redirect_url    The url that the user is coming from.
     * @param  string $expected_output The expected JSON output
     */
    protected function assertControllerResponse($method = "", $redirect_url = "", $expected_output = "")
    {
        $_SERVER['REDIRECT_URL'] = $redirect_url;

        $this->expectOutputString($expected_output);

        $controller = new $this->controller_name(rand(0,1000));
        Reflection::setProperty('allowGenerateHeader', $this->controller_name, $controller, false);
        Reflection::callMethod($method, $this->controller_name, [], $controller);
    }
}