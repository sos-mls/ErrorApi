<?php

/**
 * Contains the CreateController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\Reflection;
use Asset\Action\Image as Action_Image;

/**
 * CreateController_Test class. A PHPUnit Test case class.
 *
 * Tests image creation functions inside of the Create controller class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class CreateController_Test extends TestController
{

    const EXPECTED_HEIGHT_KEY = 'expected_height';
    const EXPECTED_WIDTH_KEY = 'expected_width';

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
     * Input
     *
     *
     *
     */

    /**
     * Contains an array of test files for creating.
     * 
     * @return array An array of files
     */
    public function input_actionAssetCreate()
    {
        return [
            [
                TestController::COMPARISON_DIRECTORY . '/contain_aspect_true.png',
                [
                    'actions' => [
                        [
                            Action_Image::NAME_KEY              => "original",
                            Action_Image::WIDTH_KEY             => 1000,
                            Action_Image::HEIGHT_KEY            => 1000,
                            Action_Image::KEEP_ASPECT_RATIO_KEY => true,
                            Action_Image::PADDING_KEY           => false,
                            self::EXPECTED_WIDTH_KEY            => 1000,
                            self::EXPECTED_HEIGHT_KEY           => 562
                        ],
                        [
                            Action_Image::NAME_KEY    => "thumbnail",
                            Action_Image::WIDTH_KEY   => 150,
                            Action_Image::HEIGHT_KEY  => 150,
                            self::EXPECTED_WIDTH_KEY  => 150,
                            self::EXPECTED_HEIGHT_KEY => 150
                        ]
                    ]
                ]
            ],
            [
                TestController::COMPARISON_DIRECTORY . '/contain_aspect_true.png',
                [
                    'actions' => [
                        [
                            Action_Image::NAME_KEY              => "original",
                            Action_Image::WIDTH_KEY             => 2000,
                            Action_Image::HEIGHT_KEY            => 2000,
                            Action_Image::KEEP_ASPECT_RATIO_KEY => true,
                            Action_Image::PADDING_KEY           => false,
                            self::EXPECTED_WIDTH_KEY            => 2000,
                            self::EXPECTED_HEIGHT_KEY           => 1125
                        ],
                        [
                            Action_Image::NAME_KEY    => "thumbnail",
                            Action_Image::WIDTH_KEY   => 370,
                            Action_Image::HEIGHT_KEY  => 280,
                            self::EXPECTED_WIDTH_KEY  => 370,
                            self::EXPECTED_HEIGHT_KEY => 280
                        ],
                        [
                            Action_Image::NAME_KEY    => "thumbnail",
                            Action_Image::WIDTH_KEY   => 152,
                            Action_Image::HEIGHT_KEY  => 154,
                            self::EXPECTED_WIDTH_KEY  => 152,
                            self::EXPECTED_HEIGHT_KEY => 154
                        ]
                    ]
                ]
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
     * Tests the actionIndex method errpr resonse.
     */
    public function test_actionIndexError()
    {
        $expected_output = "HTTP/1.1 424 \n" .
            "Content-type: application/json\n" .
            '{"errors":{"general":["Not a proper http method type, please send a FILE"]}}';

        $this->assertControllerResponse('actionIndex',  '/create/', $expected_output);
    }

    /**
     * Adds a file to the $_FILE variable and calls the create controller
     * 
     * @dataProvider input_actionAssetCreate
     * 
     * @param  string $file_path [description]
     * @param  array  $post      
     */
    public function test_actionAssetCreate($file_path = "", array $post = [])
    {
        $_FILES = [
            'file' => [
                'tmp_name' => $file_path,
                'name' => 'Hello'
            ]
        ];

        $_POST = $post;

        $expected_output = "HTTP/1.1 200 OK\n" .
                "Content-type: application/json\n" .
                '{"success":"Asset will be deleted."}';

        $_SERVER['REDIRECT_URL'] = '/create/';
        ob_start();
        $controller = new $this->controller_name(rand(0,1000));
        Reflection::setProperty('allowGenerateHeader', $this->controller_name, $controller, false);
        Reflection::callMethod('actionIndex', $this->controller_name, [], $controller);
        $response = ob_get_contents();
        ob_end_clean();
        
        $json_response = str_replace("HTTP/1.1 200 OK\n", "", $response);
        $json_response = str_replace("Content-type: application/json\n", "", $json_response);
        $asset_json = json_decode($json_response);
        
        $this->assertTrue(Asset::model()->fileName($asset_json->public_url)->exists());
        if (Asset::model()->fileName($asset_json->public_url)->exists()) {
            $this->assertCreationEquals(Asset::model()->fileName($asset_json->public_url)->find());
        }
    }

    /**
     * Confirms that the asset images created are of same style as the actions passed 
     * in the post.
     * 
     * @param  Asset  $asset The asset created.
     */
    private function assertCreationEquals(Asset $asset) {
        foreach ($_POST['actions'] as $action) {
            $size_exists = false;

            foreach ($asset->images as $image) {
                if ($image->width == $action[self::EXPECTED_WIDTH_KEY] &&
                    $image->height == $action[self::EXPECTED_HEIGHT_KEY]) {
                    $size_exists = true;
                }
            }

            $this->assertTrue($size_exists);
        }
    }
}