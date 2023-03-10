<?php
    spl_autoload_extensions(".php");
    spl_autoload_register();

    use yasmf\Router;
    use yasmf\DataSource;
    
    $dataSource = new DataSource(
        $host = 'localhost',
        $port = '3306', # to change with the port your mySql server listen to
        $db = 'cym', # to change with your db name
        $user = 'root', # to change with your db user name
        $pass = 'root', # to change with your db password
        $charset = 'utf8mb4'
      );      

    $router = new Router() ;
    $router->route($dataSource);
?>