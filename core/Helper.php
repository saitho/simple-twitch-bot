<?php
/**
 *
 *     |o     o    |          |
 * ,---|.,---..,---|,---.,---.|__/
 * |   |||   |||   ||---'`---.|  \
 * `---'``---|``---'`---'`---'`   `
 *       `---'    [media solutions]
 *
 * @copyright   (c) digidesk - media solutions
 * @link            http://www.digidesk.de
 * @author          digidesk - media solutions
 * @version         Git: $Id$
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