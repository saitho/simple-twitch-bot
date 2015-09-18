<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

class DB extends PDO
{
    /**
     * @var
     */
    private static $__instance;


    /**
     * @var string
     */
    private static $__sHost = 'localhost';


    /**
     * @var string
     */
    private static $__sUser = 'mysqluser';


    /**
     * @var string
     */
    private static $__sPassword = 'password';


    /**
     * @var string
     */
    private static $__sDatabase = 'database';


    /**
     * @var string
     */
    private static $__sCharset = 'utf8';


    /**
     * @var int
     */
    private static $__iPort = 3306;


    /**
     * @return PDO
     */
    public static function getInstance()
    {
        if ( !self::$__instance )
        {
            self::$__instance = new PDO( 'mysql:host=' . self::$__sHost . ';dbname=' . self::$__sDatabase . ';charset=' . self::$__sCharset . ';', self::$__sUser, self::$__sPassword );
        }

        return self::$__instance;
    }
}