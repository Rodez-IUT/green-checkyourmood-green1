<?php
const PREFIX_TO_RELATIVE_PATH = "/green-checkyourmood-green1/app_CYM";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use application\DefaultComponentFactory;
use yasmf\DataSource;
use yasmf\Router;

$data_source = new DataSource(
    $host = 'localhost',
    $port = 3306, # to change with the port your mySql server listen to
    $db_name = 'cym', # to change with your db name
    $user = 'root', # to change with your db username
    $pass = 'root', # to change with your db password
    $charset = 'utf8mb4'
);

$router = new Router(new DefaultComponentFactory()) ;
$router->route(PREFIX_TO_RELATIVE_PATH,$data_source);
