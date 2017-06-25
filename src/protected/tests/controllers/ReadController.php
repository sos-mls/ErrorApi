<?php

/**
 * Contains the ReadController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\Reflection;
use Asset\Action\Image as Action_Image;
use Asset\File\Image as File_Image;

/**
 * ReadController_Test class. A PHPUnit Test case class.
 *
 * Tests image delete functions inside of the Delete controller class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class ReadController_Test extends TestController
{

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'ReadController';
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
     * Gives the method, the redirect url and the expected json output.
     * 
     * @return array An array of the method name, redirect url, and expectedJSON.
     */
    public function input_actionMethodError()
    {
        return [
            [
                "actionAsset",
                "/read/asset/abc123",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Asset not found."]}}'
            ],
            [
                "actionAsset",
                "/read/asset",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Please send the asset file_name"]}}'
            ],
            [
                "actionImage",
                "/read/image/abc123",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Image not found."]}}'
            ],
            [
                "actionImage",
                "/read/image",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Please send the image file_name"]}}'
            ]
        ];
    }

    /**
     * Contains an array of test files for uploading.
     * 
     * @return array An array of files
     */
    public function input_actionAssetRead()
    {
        return [
            [
                TestController::COMPARISON_DIRECTORY . '/contain_aspect_true.png'
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
     * Tests the actionAsset method error responses.
     *
     * @dataProvider input_actionMethodError
     * 
     * @param  string $method          The action to call in the controller.
     * @param  string $redirect_url    The url that the user is coming from.
     * @param  string $expected_output The expected JSON output
     */
    public function test_actionMethodError($method = "", $redirect_url = "", $expected_output = "")
    {
        $this->assertControllerResponse($method,  $redirect_url, $expected_output);
    }

    /**
     * Creates an asset, calls the use controller and confirms the response of the 
     * read asset.
     * 
     * @dataProvider input_actionAssetRead
     * 
     * @param  string $file_path The path to a file
     */
    public function test_actionAssetRead($file_path = "")
    {
        $asset_type = AssetType::getType($file_path);
        $destination = Asset::generateDestination();
        $name = Asset::getAssetName($destination);

        $asset = new Asset();
        $asset->asset_type_id = $asset_type->asset_type_id;
        $asset->file_name = $name;
        $asset->uploaded_name = "Hello";
        $asset->created_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
        $asset->save();

        $asset = Asset::model()->fileName($asset->file_name)->find();

        $expected_output = "HTTP/1.1 200 OK\n" .
                "Content-type: application/json\n" .
                \CJSON::encode($asset->toArray());

        $this->assertControllerResponse('actionAsset',  '/read/asset/' . $asset->file_name, $expected_output);
    }

    /**
     * Creates an asset and some images, gets the response of the images that were
     * created and compares them with the actual images.
     * 
     * @dataProvider input_actionAssetRead
     * 
     * @param  string $file_path The path to a file
     */
    public function test_actionImageRead($file_path = "")
    {
        $asset_type = AssetType::getType($file_path);
        $destination = Asset::generateDestination();
        $name = Asset::getAssetName($destination);

        $action_results = File_Image::forge(
            $file_path,
            Yii::app()->params->asset_library['valid_types'],
            [Yii::app()->params->asset_library['actions'][0]]
        )->act();

        $asset = new Asset();
        $asset->asset_type_id = $asset_type->asset_type_id;
        $asset->file_name = $name;
        $asset->uploaded_name = "Hello";
        $asset->created_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
        $asset->save();

        $image = null;

        foreach ($action_results as $action_result) {
            $destination = Asset::generateDestination();
            $name = Asset::getAssetName($destination);

            if (!rename($action_result[Action_Image::PATH_KEY], $destination)) {
                $this->assertTrue(false, "Could not save the image. Please Try again.");
            }

            $image = new Image();
            $image->asset_id = $asset->asset_id;
            $image->file_name = $name;
            $image->file_size = filesize($destination);
            list($image->width, $image->height) = getimagesize($destination);
            $image->created_at = str_replace("+0000", "Z", date(DATE_ISO8601, getdate()[0]));
            $image->save();

            $asset->addErrors($image->getErrors());
        }

        $image = Image::model()->fileName($image->file_name)->find();

        $expected_output = "HTTP/1.1 200 OK\n" .
                "Content-type: application/json\n" .
                file_get_contents(Asset::getAssetDir() . $image->file_name);

        $_SERVER['REDIRECT_URL'] = '/read/image/' . $image->file_name;
        ob_start();
        $controller = new ReadController(rand(0,1000));
        Reflection::setProperty('allowGenerateHeader', 'ReadController', $controller, false);
        Reflection::callMethod('actionImage', 'ReadController', [], $controller);
        $response = ob_get_contents();
        ob_end_clean();

        $this->assertContains(file_get_contents(Asset::getAssetDir() . $image->file_name), $response);
    }
}