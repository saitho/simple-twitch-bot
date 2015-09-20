<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

class Config
{
    /**
     * @var Config
     */
    private static $__instance;


    /**
     * @var array
     */
    private $__aConfig = array();


    /**
     * Parses configs/config.ini file and saves it to an array
     */
    public function __construct()
    {
        $this->__aConfig = parse_ini_file( getBasePath() . 'configs/config.ini', false, INI_SCANNER_NORMAL );
        cliLog( "Configuration loaded from configs/config.ini", 'SETUP' );
    }


    /**
     * @return Config
     */
    public static function getInstance()
    {
        if ( !self::$__instance )
        {
            self::$__instance = new self;
        }

        return self::$__instance;
    }


    /**
     * Returns config value
     *
     * @param $sKey
     *
     * @return null|mixed
     */
    public function get( $sKey )
    {
        return isset( $this->__aConfig[ $sKey ] ) ? $this->__aConfig[ $sKey ] : null;
    }


    /**
     * Helper function to get a language string from the config.
     *
     * @param string $sKey
     * @param array  $aParams
     *
     * @return mixed|null
     */
    public function lang( $sKey, $aParams = array() )
    {
        $sTranslated = '';
        $sLanguage   = $this->__aConfig[ 'app.language' ];

        if( !empty( $sLanguage ) )
        {
            $sKey        = $sLanguage . '.' . $sKey;
            $sTranslated = isset( $this->__aConfig[ $sKey ] ) ? $this->__aConfig[ $sKey ] : null;

            if( !empty( $sTranslated ) && count( $aParams ) )
            {
                $sTranslated = sprintf( $sTranslated, $aParams );
            }
        }

        return $sTranslated;
    }


    /**
     * Helper function to check if a nick has mod status.
     *
     * @param string $sNick
     *
     * @return bool
     */
    public function isMod( $sNick )
    {
        return in_array( $sNick, explode( ',', $this->get( 'app.mods' ) ) );
    }
}