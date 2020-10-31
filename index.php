<?php

require_once 'config.php';
require_once 'vendor/autoload.php';

$bootsrtap = new Core\Bootstrap();

$bootsrtap->session();
$bootsrtap->errorReporting();
$bootsrtap->parseUrlPath();
$bootsrtap->app();

/**
 * @param mixed $expression
 */
function _dump($expression)
{
    echo '<pre>';
    print_r($expression);
    echo '</pre>';
}
