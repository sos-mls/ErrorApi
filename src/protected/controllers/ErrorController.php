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
                'create' => 'https://bitbucket.org/scooblyboo/errorapi/wiki/api/Create',
                'solve'  => 'https://bitbucket.org/scooblyboo/errorapi/wiki/api/Solve',
                'read'   => 'https://bitbucket.org/scooblyboo/errorapi/wiki/api/Read',
            ],
            'settings' => 'https://bitbucket.org/scooblyboo/errorapi/wiki/Settings',
            'testing' => 'https://bitbucket.org/scooblyboo/errorapi/wiki/Testing'
        ]);
    }
}
