<?php

/**
 * Contains the DeleteController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\Reflection;
use Asset\Action\Image as Action_Image;
use Asset\File\Image as File_Image;

/**
 * DeleteController_Test class. A PHPUnit Test case class.
 *
 * Tests image delete functions inside of the Delete controller class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class DeleteController_Test extends TestController
{

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'DeleteController';
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
     * Gives the redirect url and the expected json output.
     * 
     * @return array An array of the redirect url, and expectedJSON.
     */
    public function input_actionAssetError()
    {
        return [
            [
                "/delete/asset/abc123",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Asset not found."]}}'
            ],
            [
                "/delete/asset",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Please send the asset file_name"]}}'
            ]
        ];
    }

    /**
     * Contains an array of test files for deleting.
     * 
     * @return array An array of files
     */
    public function input_actionAssetDelete()
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
     * @dataProvider input_actionAssetError
     * 
     * @param  string $redirect_url    The url that the user is coming from.
     * @param  string $expected_output The expected JSON output
     */
    public function test_actionAssetError($redirect_url = "", $expected_output = "")
    {
        $this->assertControllerResponse('actionAsset',  $redirect_url, $expected_output);
    }

    /**
     * Creates an asset, calls the use controller and confirms the response along the used asset.
     * 
     * @dataProvider input_actionAssetDelete
     */
    public function test_actionAssetDelete($file_path = "")
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
        $asset->is_used = Asset::IS_USED;
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
                '{"success":"Asset will be deleted."}';

        $this->assertControllerResponse('actionAsset',  '/delete/asset/' . $asset->file_name, $expected_output);

        $asset = Asset::model()->fileName($asset->file_name)->find();

        $this->assertEquals(Asset::IS_NOT_USED, $asset->is_used);
    }
}