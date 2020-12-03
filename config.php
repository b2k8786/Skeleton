<?php

/**
 * Environment
 */
define('API_MODE', false);

/*
 * DB Configuration
 */

define('DB_TYPE', 'pgsql'); // pgsql || mysql
define('DB_DATABASE', 'main');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'qwerty');


define('APP_ROOT','Skeleton/');
define('BASE_PATH', __DIR__ . '/');
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('CONTROLLER_NS', 'App\controllers\\');
define('DEFAULT_CONTROLLER', 'Main');
define('VIEW_PATH', 'App/view/');
define('frontend', BASE_URL . 'frontend/');
define('ERROR_REPOTING', true);
define('SALT', 'FABE114254BDBC7823534894FFFCCC1');

define('is_log', 0);
define('log_storage', 'DB'); //DB or FILE

/**
 * @var array() Rest configuration
 */
$method = [
    'GET'    => 'home',
    'POST'   => 'add',
    'PUT'    => 'edit',
    'DELETE' => 'remove'
];
/**
 * @var array() Routes Configuration
 */
$routes = [
    '/'          => 'Test/info'
];

