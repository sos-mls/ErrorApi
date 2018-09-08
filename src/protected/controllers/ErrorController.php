<?php

/**
 * Contains the ErrorController class.
 *
 * @author  Christian Micklisch <christian.micklisch@successwithsos.com>
 */

use Common\ApiController;

/**
 * The ErrorController Acts as a default controller.
 *
 * It sends the user to the given pages to get more information about how to utilize
 * the Error Api.
 *
 * @author Christian Micklisch <christian.micklisch@successwithsos.com>
 */
class ErrorController extends ApiController
{
    /**
     * A general response for the user to get information.
     *
     * The response contains where to get general information about the ErrorApi,
     * how to utilize the ErrorApi, Configuration of the ErrorApi, and Recommendations
     * for integrating the assetApi into a server network.
     * 
     * @return JSON Information about the ErrorAPI.
     */
    public function actionIndex()
    {
        $this->renderJSON([
            'api' => [
                'create' => 'https://github.com/sos-mls/ErrorApi/wiki/API-Create',
                'solve'  => 'https://github.com/sos-mls/ErrorApi/wiki/API-Solve',
                'read'   => 'https://github.com/sos-mls/ErrorApi/wiki/API-Read',
            ],
            'settings' => 'https://github.com/sos-mls/ErrorApi/wiki/Settings',
            'testing' => 'https://github.com/sos-mls/ErrorApi/wiki/Testing'
        ]);
    }
}
