# Simple PHP Twitch.tv Bot


## Installation Notes

php ./src/Features/Currency/bin/currency

----

Section below from forked bot...


I'm proud to present you my simply customizable and extensible Twitch.tv Bot.
It's build as a boilerplate for your needs and just equipped with basic functions.

If you have any questions, you can contact me via Skype: kai_neuwerth

Feel free to contribute! :)

## Requirements

- PHP 5.2 with CLI access
- A Twitch.tv account
- Connection to the internet (*lol*)


## Setup

1. Download this repository
2. Extract it to a lovely place where you can execute PHP
3. Open ``config/config.ini`` in an editor and fill the values according to the config documentation below
4. Open ``config/static_commands.ini`` in an editor and set some commands with static answers
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


#### `app.language`

Defines which language should be used by Config 

Example: "i18n_en"


#### `app.mods`

Seperate multiples by comma without spaces! 

Example: "User1,User2"


#### `app.mem_warning` (Default: 5)

Defines how much RAM usage in MB is okay for the bot. If the RAM usage is higher than this config value there will be warnings in CLI.

Example: 5


#### `app.thread_interval` (Default: 0.2)

Defines how long the loop of the main thread sleeps before looking for new messages (in seconds).

Example: 0.2


#### `features.coin_system` (Default: 0)

Not implemented yet :'(

Example: 1


### Predefined commands

#### `!commands`

Sends all available commands in the chat.


#### `!credits`

This is a static command that can also be removed in ``config/static_commands.ini``.


#### `!donate`

This is a static command that can also be removed in ``config/static_commands.ini``.


#### `!example`

This is a static command that can also be removed in ``config/static_commands.ini``.


#### `!help` (alias for `!commands`)

Sends all available commands in the chat.


#### `!queue`

This is a queue manager for viewergames (example usage).

Available commands:

- !queue join <nick>
- !queue get <amount> (mod only)
- !queue list (mod only)
- !queue clear (mod only)


#### `!welcome`

Says hello to the sender or first command parameter.

Available commands:

- !welcome
- !welcome <nick>


#### `!whoami`

This is a static command with a placeholder that can also be removed in ``config/static_commands.ini``.


### Adding own command classes

Sure, it's possible but I haven't finished the documentation.

Maybe it helps you to create one by copying the ``commands/Test_Command.php`` and try something on your own.

Documentation coming later...
