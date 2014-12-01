# Console commands

## Introduction

I won't be explaining each and every command in this document (most of the information can be found under each command's
`--help` option anyway).

Instead, I will just show you an example for two of the commands, `slack:chat:post-message` (sending a message to a
Slack channel) and `slack:auth:test` (testing token authentication for debugging purposes).

Like I said, all of the available commands have a help message that you can check out for more information,
but the examples below should help to get you started.


### About tokens (`--token`)

All commands use the token you have configured under `cl_slack.api_token` by default. You can override this for your
command by using the `--token` option:
```
$ php app/console slack:some:command arg1 arg2 --token=my-token
```

### About verbosity (`-v`)

**HINT 1:** If you use the `normal` verbosity option (`-v`), displays more data on the response that are less relevant
for other (quiet) scenarios.
**HINT 2:** If you use the `verbose` verbosity option (`-vv`), debugging information is displayed about the data sent
*to* and *from* the Slack API (including token).


## Sending a message (`slack:chat:post-message`)

Here are some examples of sending a message to a Slack channel, using ther `slack:chat:post-message` command.

Simple example (no verbosity):
```
$ php app/console slack:chat:post-message general "This is a test" --username=AcmeBot --icon-emoji=truck
✔ Successfully sent message to Slack!
```

Detailed example, (using normal verbosity `-v`):
```
$ php app/console slack:api:chat-post-message general 'This is a test' -v
✔ Successfully sent message to Slack!
Channel ID: C01234567
Timestamp: 1234567890
```

Debugging request/response example (using verbose verbosity `-vv`):
```
$ php app/console slack:api:chat-post-message general 'This is a test' -vv
Debug: sending payload...
+----------+----------------------------------------------+
| channel  | #general                                     |
| text     | This is a test                               |
| token    | your-token-here                              |
+----------+----------------------------------------------+
Debug: received payload response...
+---------+-------------------+
| ok      | 1                 |
| channel | C01234567         |
| ts      | 1234567890.123456 |
+---------+-------------------+
✔ Successfully sent message to Slack!
Channel ID: C01234567
Timestamp: 1234567890.123456
```

## Testing authentication (`slack:auth:test`)

You might want to know who you are authenticated as during authorization.

```
$ php app/console slack:api:auth-test -v
✔ Successfully authenticated by the Slack API!
```

Again, the verbosity option (`-v`) comes in handy:
```
$ php app/console slack:api:auth-test -v
✔ Successfully authenticated by the Slack API!
+----------+------------+
| User ID  | U01234567  |
| Username | my-name    |
| Team ID  | T01234567  |
| Team     | my-team    |
+----------+------------+
```

## Got it?

That's about it for the documentation part of this bundle, be sure to check out the [library's documentation](https://github.com/cleentfaar/slack/Resources/doc/usage.md)
for a more detailed explanation of the ways you can interact with your team's Slack API using payload classes and responses.

I am also open to PRs if you find things you would like to see changed! Before you do this, check out the documentation
about contributing to this package [here](contributing.md).
