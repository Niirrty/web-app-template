<?php
/**
 * @author         Ni Irrty <niirrty+code@gmail.com>
 * @copyright  (c) 2017-2026, Ni Irrty
 * @since          2017-12-13
 * @version        0.6.4
 */

declare( strict_types=1 );


namespace Niirrty\Example\Routes;


use Niirrty\Locale\Locale;
use Niirrty\Plate\Engine;
use Niirrty\Routing\UrlPathLocator\ILocator;
use Niirrty\Translation\Translator;


abstract class AbstractRoute
{


    #region –––––––   P R O T E C T E D   F I E L D S   ––––––––––––––––––––––––––––––––––

    /**
     * The template engine
     *
     * @var Engine|null
     */
    protected ?Engine $_engine;

    /**
     * The template engine
     *
     * @var Locale
     */
    protected Locale $_locale;

    /**
     * The template engine
     *
     * @var Translator
     */
    protected Translator $_trans;

    /**
     * @var ILocator|null
     */
    protected ?ILocator $_urlPathLocator;

    #endregion


    #region –––––––   C O N S T R U C T O R   A N D / O R   D E S T R U C T O R   ––––––––

    /**
     * AbstractRoute constructor.
     *
     * @param ILocator|null   $urlPathLocator
     * @param Engine|null     $tplEngine
     * @param Locale          $locale
     * @param Translator      $translator
     */
    public function __construct(
        ?ILocator $urlPathLocator, ?Engine $tplEngine, Locale $locale, Translator $translator )
    {

        $this->_engine = $tplEngine;
        $this->_locale = $locale;
        $this->_trans = $translator;
        $this->_urlPathLocator = $urlPathLocator;

    }

    #endregion


    #region –––––––   P U B L I C   M E T H O D S   ––––––––––––––––––––––––––––––––––––––

    /**
     * @param array $options Optional running options :-)
     */
    public abstract function run( array $options = [] );


    #endregion


}

