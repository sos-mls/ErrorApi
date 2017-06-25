<?php

/**
 * Contains the AssetController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

/**
 * AssetController_Test class. A PHPUnit Test case class.
 *
 * Tests specific functions inside of the Asset controller class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class AssetController_Test extends TestController
{

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'AssetController';
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
            '{"api":{"create":"https:\/\/bitbucket.org\/scooblyboo\/assetapi\/wiki\/api\/Create","use":"https:\/\/bitbucket.org\/scooblyboo\/assetapi\/wiki\/api\/Use","read":"https:\/\/bitbucket.org\/scooblyboo\/assetapi\/wiki\/api\/Read","delete":"https:\/\/bitbucket.org\/scooblyboo\/assetapi\/wiki\/api\/Delete"},"settings":"https:\/\/bitbucket.org\/scooblyboo\/assetapi\/wiki\/Settings","testing":"https:\/\/bitbucket.org\/scooblyboo\/assetapi\/wiki\/Testing"}';

        $this->assertControllerResponse('actionIndex', '/asset/', $expectedOutput);
    }
}