<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-02-05
 * @version        0.1.0
 */


declare( strict_types = 1 );


namespace Niirrty\Example;


use Niirrty\DB\Driver\SQLite as SQLiteDriver;
use Niirrty\DB\Connection as DbConnection;
use Niirrty\Example\Routes\Fallback;
use Niirrty\Example\Routes\Home;
use Niirrty\IO\Vfs\Handler as VfsHandler;
use Niirrty\IO\Vfs\Manager as VfsManager;
use Niirrty\Locale\Locale;
use Niirrty\Plate\Config as TplConfig;
use Niirrty\Plate\Engine as TplEngine;
use Niirrty\Routing\Router;
use Niirrty\Routing\UrlPathLocator\ILocator;
use Niirrty\Routing\UrlPathLocator\RequestUri as RequestUriLocator;
use Niirrty\Translation\Sources\PHPFileSource;
use Niirrty\Translation\Translator;
use Pimple\ServiceProviderInterface;
use Pimple\Container;


class ApplicationServiceProvider implements ServiceProviderInterface
{


   // <editor-fold desc="// –––––––   P U B L I C   M E T H O D S   ––––––––––––––––––––––––––––––––––––––">


   public function register( Container $pimple )
   {


      // Define if this is  executed in development mode
      $pimple[ 'is_dev_mode' ] = false;

      // The name of the session storage, used to store auth depending information
      $pimple[ 'auth_storage_name' ]     = 'auth';


      // Virtual file system
      $this->registerVfs( $pimple );


      // Locale and Translation
      $this->registerLocaleAndTranslator( $pimple );


      // Database driver and connection
      $this->registerDbConnection( $pimple );


      // "Plate" template engine config + init
      $this->registerTemplateEngine( $pimple );


      $this->registerRoutes( $pimple );


   }


   // </editor-fold>


   // <editor-fold desc="// –––––––   P R I V A T E   M E T H O D S   ––––––––––––––––––––––––––––––––––––">

   private function registerVfs( Container $pimple )
   {

      // VFS handler 'files://'
      $pimple[ 'vfs_files_handler' ] = function()
      {
         return VfsHandler::Create( 'Application files root folder' )
                       ->setProtocol( 'files', '://' )
                       ->setRootFolder( \dirname( \dirname( __DIR__ ) ) . '/files' );
      };


      // The VFS Manager. It manages all VFS handlers
      $pimple[ 'vfs_manager' ]       = function( $c )
      {
         return VfsManager::Create()->addHandler( $c[ 'vfs_files_handler' ] );
      };

   }

   private function registerLocaleAndTranslator( Container $pimple )
   {

      // This are the names of the parameters, accepted from $_POST, $_GET and $_SESSION
      $pimple[ 'locale_request_fields' ] = [ 'locale', 'language', 'lang' ];


      // The fallback locale if no other was found
      $pimple[ 'default_locale' ]        = new Locale( 'de', 'DE', 'UTF-8' );


      // The Locale
      $pimple[ 'locale' ]                = function( $c )
      {
         return Locale::Create( $c[ 'default_locale' ], true, $c[ 'locale_request_fields' ] );
      };


      // The source of translations
      $pimple[ 'translation_source' ]    = function( $c )
      {
         return new PHPFileSource( 'files://i18n', $c[ 'vfs_manager' ] );
      };


      // The translator, depending to 'locale' and 'translation_source'
      $pimple[ 'translator' ]            = function( $c )
      {
         return ( new Translator( $c[ 'locale' ] ) )->addSource( '_', $c[ 'translation_source' ] );
      };

   }

   private function registerDbConnection( Container $pimple )
   {

      // The DB driver (MySQL in this case)
      $pimple[ 'db_driver' ]             = function( Container $pimple )
      {
         return ( new SQLiteDriver() )->setDb( $pimple[ 'vfs_manager' ]->parsePath( 'files://data/example.sqlite' ) );
         // return DriverFactory::FromConfigFile( 'files://config/mysql-driver-config.yaml' );
         // return DriverFactory::FromConfigFile( 'files://config/pgsql-driver-config.yaml' );
      };

      /// The DB connection
      $pimple[ 'db_connection' ]         = function( $c )
      {
         return new DBConnection( $c[ 'db_driver' ] );
      };

   }

   private function registerTemplateEngine( Container $pimple )
   {

      // The template engine config
      $pimple[ 'tpl_config' ]            = function( $c )
      {
         return TplConfig::FromINIFile( 'files://config/plate-config.ini', 'ini', $c[ 'vfs_manager' ] )
                         ->setCacheMode(
                            $c[ 'is_dev_mode' ]
                               ? TplConfig::CACHE_MODE_EDITOR
                               : TplConfig::CACHE_MODE_USER
                         );
      };

      // The "Plate" template engine
      $pimple[ 'tpl_engine' ]            = function( $c )
      {
         return new TplEngine( $c[ 'tpl_config' ] );
      };

   }

   private function registerRoutes( Container $pimple )
   {

      // Get the current called not existing URL path by $_SERVER[ 'REQUEST_URI' ]
      $pimple[ 'url_path_locator' ] = function()
      {

         return new RequestUriLocator();

      };

      $pimple[ 'route_fallback' ] = function( ILocator $locator ) use( $pimple )
      {

         ( new Fallback( $locator, $pimple[ 'tpl_engine' ], $pimple[ 'locale' ], $pimple[ 'translator' ] ) )
            ->run();

         return true;

      };

      $pimple[ 'route_home' ] = function( ILocator $locator ) use( $pimple )
      {

         ( new Home( $locator, $pimple[ 'tpl_engine' ], $pimple[ 'locale' ], $pimple[ 'translator' ] ) )
            ->run();

         return true;

      };

      $pimple[ 'router' ] = function( Container $c )
      {

         return Router::CreateInstance()

            // Handling URL paths, not declared by a route
            ->setFallBackHandler( $c->raw( 'route_fallback' ) )

            // Redirect direct /index.php calls to /
            ->addMultiPathStaticRedirection( [ '/index.html', 'index.php' ], '/' )

            // Application Home
            ->addSimpleRoute( '/', $c->raw( 'route_home' ) );

         /*->addRegexRoute( '~^/services/([A-Za-z0-9_.:-]+)/?$~', [
            function( $matches ) { // $matches[ 1 ] defines the first part inside parenthesises, and so on
               echo '<pre>';
               print_r( $matches );
               exit;
            }
         ] )*/
      };

   }

   // </editor-fold>


}

