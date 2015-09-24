<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

// Clearing Log File
file_put_contents( getBasePath() . 'cli.log', "" );

/**
 * Helper-Method for CLI logging.
 *
 * @param string $sMessage
 * @param string $sType
 */
function cliLog( $sMessage, $sType = 'INFO' )
{
    $sLog = '[' . date( 'Y-m-d H:i:s' ) . '] [' . str_pad( $sType, 11, ' ', STR_PAD_BOTH ) . '] ' . $sMessage . "\n";

    echo $sLog;
    file_put_contents( getBasePath() . 'cli.log', $sLog, FILE_APPEND );

    if( $sType == 'CRITICAL' )
    {
        die( "Exiting.\n" );
    }
}


function getBasePath()
{
    return realpath( dirname( __FILE__ ) . '/../' ) . '/';
}