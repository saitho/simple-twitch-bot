<?php
/**
 * @link        https://github.com/Crease29/simple-twitch-bot
 * @author      Kai Neuwerth <github.com/Crease29>
 */

class Test_Command extends Command
{
    /**
     * Defines if a command is public. If a command is mod-only it wont be public.
     *
     * @var bool
     */
    protected $_blIsPublic = false;


    /**
     * RegEx pattern to get the triggered
     *
     * @var string
     */
    protected $_sCmdPattern = "/^!test$/i";


    /**
     * Command that will be listed in the available commands in the chat
     *
     * @var string
     */
    protected $_sReadablePattern = '!test';


    /**
     * Can be used as hook before a command is executed.
     *
     * @return void
     */
    public function onBeforeExecute()
    {
        parent::onBeforeExecute();
    }


    /**
     * Can be used as hook when a command is *really* executed.
     *
     * @return void
     */
    public function doExecute()
    {
        parent::doExecute();

        $this->setReturnMessage( 'Test successful!' );
    }


    /**
     * Can be used as hook after a command is executed.
     *
     * @return void
     */
    public function onAfterExecute()
    {
        parent::onAfterExecute();
    }
}