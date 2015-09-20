<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

/**
 * Helper-Method for CLI logging.
 *
 * @param string $sMessage
 * @param string $sType
 */
function cliLog( $sMessage, $sType = 'INFO' )
{
    echo '[' . str_pad( $sType, 11, ' ', STR_PAD_BOTH ) . '] ' . $sMessage . "\n";

    if( $sType == 'CRITICAL' )
    {
        die( "Exiting.\n" );
    }
}


function getBasePath()
{
    return realpath( dirname( __FILE__ ) . '/../' ) . '/';
}