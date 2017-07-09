<?php
return [
    // this is displayed in the header section
    'title' => 'Error Api',

    // the login duration when a user selects 'remember me'
    'loginDuration' => 3600 * 24 * 30, // 30 days

    // this is used in error pages
    'adminEmail' => 'services@errorapi.milf.com',

    // the copyright information displayed in the footer section
    'copyrightInfo' => 'Copyright &copy; 2017 by Error Api',

    // The date format used by the database
    'dbDateFormat' => 'Y-m-d H:i:s',

    'relative_error_dir' =>array_key_exists('HTTP_HOST', $_SERVER) ?  '//' . $_SERVER['HTTP_HOST'] . '/read/error/' : '',
];