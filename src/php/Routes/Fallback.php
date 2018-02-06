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


class Fallback extends AbstractRoute
{


   /**
    * @param array $options Optional running options :-)
    */
   public function run( array $options = [] )
   {

      echo 'FOOOO!¡!'; exit;

      # TODO: Here the code must be placed that outputs if a requested URL have not an associated router.

   }


}

