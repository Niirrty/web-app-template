<?php


session_start();


include dirname( __DIR__ ) . '/vendor/autoload.php';


try
{

   // Use Pimple for dependency injection
   $container = new Pimple\Container();

   $container->register( new \Niirrty\Example\ApplicationServiceProvider() );

   $container[ 'router' ]->call( $container[ 'url_path_locator' ] );

}
catch ( \Throwable $ex )
{

   echo $ex;
   exit;

}

