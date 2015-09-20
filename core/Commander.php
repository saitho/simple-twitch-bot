<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

class Commander
{
    /**
     * @var array
     */
    private $__aStaticCommands = array();

    /**
     * @var array
     */
    private $__aCommands = array();

    /**
     * @var array
     */
    private $__aReadableCommands = array();


    public function __construct()
    {
        cliLog( "Setting up the Commander", 'SETUP' );
        $this->_setupCommands();
    }


    /**
     * Sets up all commands that are available.
     */
    protected function _setupCommands()
    {
        require_once getBasePath() . 'core/Command.php';

        $aCommands = glob( getBasePath() . 'commands/*_Command.php' );

        // Adding dynamic commands
        foreach ( $aCommands as $sCommandClass )
        {
            require_once $sCommandClass;
            $sClassName = basename( $sCommandClass, '.php' );

            $this->addCommand( $sClassName );
        }

        // Adding static commands
        $this->__aStaticCommands = parse_ini_file( getBasePath() . 'configs/static_commands.ini' );
        if( count( $this->__aStaticCommands ) )
        {
            foreach ( $this->__aStaticCommands as $sCommand => $sMessage )
            {
                $this->__aReadableCommands[] = '!' . $sCommand;
            }
        }

        cliLog( "Available commands: " . implode( ', ', $this->__aReadableCommands ), 'SETUP' );
    }


    /**
     * Returns the command list
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->__aCommands;
    }


    /**
     * Returns the static command list
     *
     * @return array
     */
    public function getStaticCommands()
    {
        return $this->__aStaticCommands;
    }


    /**
     * Returns the static command list
     *
     * @param string $sCommand
     *
     * @return string
     */
    public function getStaticCommand( $sCommand )
    {
        return $this->__aStaticCommands[ $sCommand ];
    }


    /**
     * Returns the command list
     *
     * @return array
     */
    public function getReadableCommands()
    {
        return $this->__aReadableCommands;
    }


    /**
     * Returns the given command object
     *
     * @param string $sCommandName
     *
     * @return Command
     */
    public function getCommand( $sCommandName )
    {
        return $this->__aCommands[ $sCommandName ][ 'oCommand' ];
    }


    /**
     * Adds a command to the command list
     *
     * @param string  $sCommandName
     */
    public function addCommand( $sCommandName )
    {
        if( class_exists( $sCommandName ) )
        {
            cliLog( "Adding command: {$sCommandName}", 'SETUP' );

            /** @var Command $oCommand */
            $oCommand = new $sCommandName;

            $this->__aCommands[ $sCommandName ] = array(
                'sPattern'         => $oCommand->getCommandPattern(),
                'sReadablePattern' => $oCommand->getReadableCommandPattern(),
                'oCommand'         => $oCommand,
            );

            if( $oCommand->isPublic() )
            {
                $this->__aReadableCommands[] = $oCommand->getReadableCommandPattern();
            }
        }
    }


    /**
     * Checks if the message matches a command and returns the output from the command, if some is returned.
     *
     * @param $sMessage
     * @param $sFrom
     *
     * @return string
     */
    public function processMessage( $sMessage, $sFrom )
    {
        $sReturn = '';

        // Checking for static command
        $aMessage = explode( ' ', $sMessage );
        if( is_array( $aMessage ) && in_array( substr( $aMessage[ 0 ], 1 ), array_keys( $this->getStaticCommands() ) ) )
        {
            cliLog( "Static command found: " . $aMessage[ 0 ], 'COMMANDER' );
            $sReturn = sprintf( $this->getStaticCommand( substr( $aMessage[ 0 ], 1 ) ), '@' . $sFrom );
        }

        // Checking for dynamic command
        foreach ( $this->getCommands() as $aCommand )
        {
            if( preg_match( $aCommand[ 'sPattern' ], $sMessage ) )
            {
                cliLog( "Command found: " . $aCommand[ 'sReadablePattern' ], 'COMMANDER' );

                $sReturn = $aCommand[ 'oCommand' ]->execute( $sMessage, $sFrom );
                break;
            }
        }

        return $sReturn;
    }
}