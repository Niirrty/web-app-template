<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017, Ni Irrty
 * @since          13.12.17
 * @version        0.1.0
 */


declare( strict_types=1 );


namespace Niirrty\Example\Routes;


use Niirrty\Locale\Locale;
use Niirrty\Plate\Engine;
use Niirrty\Routing\UrlPathLocator\ILocator;
use Niirrty\Translation\Translator;


abstract class AbstractRoute
{


    // <editor-fold desc="// –––––––   P R O T E C T E D   F I E L D S   ––––––––––––––––––––––––––––––––––">


    /**
     * The template engine
     *
     * @type Engine
     */
    protected $_engine;

    /**
     * The template engine
     *
     * @type Locale
     */
    protected $_locale;

    /**
     * The template engine
     *
     * @type Translator
     */
    protected $_trans;

    /**
     * @type ILocator
     */
    protected $_urlPathLocator;

    // </editor-fold>


    // <editor-fold desc="// –––––––   C O N S T R U C T O R   A N D / O R   D E S T R U C T O R   ––––––––">

    /**
     * AbstractRoute constructor.
     *
     * @param ILocator   $urlPathLocator
     * @param Engine     $tplEngine
     * @param Locale     $locale
     * @param Translator $translator
     */
    public function __construct(
        ?ILocator $urlPathLocator, ?Engine $tplEngine, Locale $locale, Translator $translator )
    {

        $this->_engine = $tplEngine;
        $this->_locale = $locale;
        $this->_trans = $translator;
        $this->_urlPathLocator = $urlPathLocator;

    }

    // </editor-fold>


    // <editor-fold desc="// –––––––   P U B L I C   M E T H O D S   ––––––––––––––––––––––––––––––––––––––">

    /**
     * @param array $options Optional running options :-)
     */
    public abstract function run( array $options = [] );


    // </editor-fold>


}

