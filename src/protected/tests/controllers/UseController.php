<?php

/**
 * Contains the UseController_Test class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\Reflection;

/**
 * UseController_Test class. A PHPUnit Test case class.
 *
 * Tests asset usage function inside of the Use controller class.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */

class UseController_Test extends TestController
{
    protected $fixtures=array(
        'asset_types'=>'AssetType'
    );

    /**
     * Sets the controller name
     */
    public function setUp()
    {
        $this->controller_name = 'UseController';
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
                "/use/asset/abc123",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Asset not found."]}}'
            ],
            [
                "/use/asset",
                "HTTP/1.1 424 \n" .
                "Content-type: application/json\n" .
                '{"errors":{"general":["Please send the asset file_name"]}}'
            ]
        ];
    }

    /**
     * Contains an array of test files for uploading.
     * 
     * @return array An array of files
     */
    public function input_actionAssetUse()
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
     * Tests the actionAsset method error response.
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
     * @dataProvider input_actionAssetUse
     */
    public function test_actionAssetUse($file_path = "")
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

        $expected_output = "HTTP/1.1 200 OK\n" .
                "Content-type: application/json\n" .
                '{"success":"Asset is now used."}';

        $this->assertControllerResponse('actionAsset',  '/use/asset/' . $name, $expected_output);

        $asset = Asset::model()->fileName($name)->find();

        $this->assertEquals(Asset::IS_USED, $asset->is_used);
    }
}