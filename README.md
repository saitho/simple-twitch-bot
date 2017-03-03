# Simple PHP Twitch.tv Bot


## Installation Notes

### Starting additional daemons from Features
e.g. php bin/run currency

### Stopping additional daemons from Features
e.g. php bin/run currency --stop

----

This is based on Crease29's Twitch bot but it comes with a different structure.

## Requirements

- PHP 5.2 with CLI access
- PEAR
- A Twitch.tv account


## Setup

1. Download this repository
2. Extract it to a lovely place where you can execute PHP
3. Run ``php composer.phar install`` to install the dependencies and craete the initial configuration
4. During the install you'll be able to configure the bot. Fill the values according to the config documentation below
5. Start the bot daemon via: ``php bin/run``
6. If you want to stop the daemon: ``php bin/run --stop``

## Config values

### [irc]

#### `server` (Default: irc.chat.twitch.tv)

List of available twitch chat servers: http://twitchstatus.com/ (Please choose one from Main Chat with ws_irc protocol)

Example: "irc.chat.twitch.tv"


#### `port` (Default: 6667)

Port of IRC server.

Example: 6667

### [bot]

#### `nick`

The twitch username of your bot.

Example: "Nightbot"

#### `oauth`

Get it from http://www.twitchapps.com/tmi/ 

Example: "oauth:abcdefghi123456789"

### [app]

#### `channelName`

The name of the channel your bot shall connect to (lowercase!).

Example: "saitholp"

#### `clientId`

clientId....

Example: "...."

#### `commandPrefix` (Default: !)

Prefix of the commands the bot shall react to.

Example: "!"

#### `language` (Default: de)

Defines which language should be used by Config 

Example: "en"


#### `mods`

Seperate multiples by comma without spaces! (lowercase)

Example: "user1,user2"

#### `blacklist`

Seperate multiples by comma without spaces (lowercase!) Example: user1,user2; users on this list can't use commands

Example: "user1,user2"


#### `mem_warning` (Default: 5)

Defines how much RAM usage in MB is okay for the bot. If the RAM usage is higher than this config value there will be warnings in CLI.

Example: 5


#### `thread_interval` (Default: 0.2)

Defines how long the loop of the main thread sleeps before looking for new messages (in seconds).

Example: 0.2

### [features]

#### `currency` (Default: 0)

If set to 1 the currency feature is activated.

Example: 1

#### `steam` (Default: 0)

If set to 1 the steam feature is activated.

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

#### `!whoami`

This is a static command with a placeholder that can also be removed in ``config/static_commands.ini``.

## Features

### General

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

#### ...
...

### Currency
The currency feature comes with an own daemon that watches the viewers and awards currency.
This daemon has to be started manually (at the moment) via: ``php bin/run currency`` (add ``--stop`` to stop it).


#### `!gold`

Prints (or whispers) the currency amount the user has.

#### `!pay (user) (amount)`

Transfers an amount to another user.