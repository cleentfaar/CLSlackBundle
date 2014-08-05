# Console Commands

All of the available commands have a help message that you can check out for more information,
but the examples below should help to get you started.

**HINT:** If you use the verbosity option (`-v`), more details are displayed about the data sent to and from Slack.


## Sending a message (chat.postMessage)

Simple example (no verbosity):
```
$ php app/console slack:api:chat-post-message general "This is a test" --username=AcmeBot --icon-emoji=truck
✔ Successfully executed API method CHAT_POSTMESSAGE
```

Detailed example, (using option `-v`):
```
$ php app/console slack:api:chat-post-message general 'This is a test' -v
✔ Successfully executed API method CHAT_POSTMESSAGE
Options sent:
+--------------+------------------------------------------------+
| Key          | Value                                          |
+--------------+------------------------------------------------+
| "channel"    | "#general"                                     |
| "text"       | "This is a test"                               |
| "icon_url"   | ""                                             |
| "icon_emoji" | ""                                             |
| "username"   | ""                                             |
| "token"      | "xoxp-123-456-789"                             |
+--------------+------------------------------------------------+
Response for this method:
+-------------+------------+
| Key         | Value      |
+-------------+------------+
| "Timestamp" | 1407273813 |
+-------------+------------+
```


## Authorization test (auth.test)

Simple example (no verbosity):
```
$ php app/console slack:api:auth-test
✔ Successfully executed API method AUTH_TEST
```

You might want to know who you who you were authenticated as during authorization.

Again, the verbosity option comes in handy:
```
$ php app/console slack:api:auth-test -v
✔ Successfully executed API method AUTH_TEST
Options sent:
+---------+------------------------------------------------+
| Key     | Value                                          |
+---------+------------------------------------------------+
| "token" | "xoxp-123-456-789-012-345"                     |
+---------+------------------------------------------------+
Response for this method:
+------------+---------------------------------+
| Key        | Value                           |
+------------+---------------------------------+
| "Username" | "cas"                           |
| "User ID"  | "U12345678"                     |
| "Team"     | "cleentfaar"                    |
| "Team ID"  | "T12345678"                     |
| "URL"      | "https://cleentfaar.slack.com/" |
+------------+---------------------------------+
```

## Got it?

If you haven't done so yet, check out the chapter about [creating a bot](creating-a-bot.md).
