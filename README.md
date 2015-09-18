# Simple Twitch.tv Bot

I'm proud to present you my simply customizable and extensible Twitch.tv Bot.


## Requirements

- PHP 5.2 with CLI access
- Connection to the internet (**lol**)


## Setup

1. Download this repository
2. Extract it to a lovely place where you can execute PHP
3. Open ``configs/config.ini`` in an editor and fill the values according to the config documentation below
4. Open ``configs/static_commands.ini`` in an editor and set some commands with static answers
5. Run via command line: ``php cli.php`` or set it up in a screen session or as a daemon (Linux)


## Documentation

### IRC config values

#### `irc.server` (Default: 192.16.64.174)

List of available twitch chat servers: http://twitchstatus.com/ (Please choose one from Main Chat with ws_irc protocol)
Example: "192.16.64.174"


#### `irc.port` (Default: 6667)

Port of IRC server.
Example: 6667


#### `irc.nick`

The twitch username of your bot.
Example: "Nightbot"


#### `irc.channels`

Seperate multiples by comma without spaces! 
Example: "#channel1,#channel2"


#### `irc.oauth`

Get it from http://www.twitchapps.com/tmi/ 
Example: "oauth:abcdefghi123456789"


### Adding own command classes

Sure, it's possible but I haven't finished the documentation.
Maybe it helps you to create one by copying the ``commands/Test_Command.php`` and try something on your own.
Documentation coming later...