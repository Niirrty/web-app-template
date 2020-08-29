<?php


session_start();


include dirname( __DIR__ ) . '/vendor/autoload.php';


try
{

   // Use Pimple for dependency injection
   $container = new Pimple\Container();

   // The application service provider init all required stuff
   $container->register( new \Niirrty\Example\ApplicationServiceProvider() );

   // Start the router for handling all called URLS
   $container[ 'router' ]->call( $container[ 'url_path_locator' ] );

}
catch ( \Throwable $ex )
{

   echo $ex;
   exit;

}

