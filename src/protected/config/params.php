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

    'relative_error_dir' => array_key_exists('HTTP_HOST', $_SERVER) ?  '//' . $_SERVER['HTTP_HOST'] . '/read/error/' : '',
    
    // one hour
    'minimum_eternal_email_execution_time' => 3600,

    // we don't want to have an email error text larger than 100 characters
    'max_error_email_length' => 100,

    'send_grid' => [
        'username'    => isset($_SERVER['send_grid_username']) ? $_SERVER['send_grid_username'] : '',
        'password'    => isset($_SERVER['send_grid_password']) ? $_SERVER['send_grid_password'] : '',
        'key'         => isset($_SERVER['send_grid_key']) ? $_SERVER['send_grid_key'] : '',
        'template_id' => isset($_SERVER['send_grid_template_id']) ? $_SERVER['send_grid_template_id'] : '',
        'name'        => 'Error API',
    ],
];