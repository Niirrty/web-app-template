<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @license        MIT
 * @since          2018-02-05
 * @version        0.1.0
 */


declare( strict_types = 1 );


namespace Niirrty\Example\Routes;


class Home extends AbstractRoute
{


   /**
    * @param array $options Optional running options :-)
    */
   public function run( array $options = [] )
   {

      # TODO: Here the code must be placed that outputs home content at the end
      $this->_engine->assign( 'locale', $this->_locale );
      $translations = $this->_trans->read( 'main', '_', [] );
      $this->_engine->assign( 'translations', $translations );
      $this->_engine->assignMulti( [
         'pageName' => 'Example-Page',
         'pageTitle' => 'Some Title'
      ] );
      $this->_engine->display( 'home.tpl' );
      exit;

   }


}

