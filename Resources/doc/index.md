# CLSlackBundle Documentation

Slack provides three ways of integrating your project with it's platform:

- [API methods](#API-methods)
    Provides interaction with the [Slack API](#).
    This can be anything like searching for a specific message or file, or sending a message to a channel.
- [Incoming webhooks](#Incoming-webhooks)
    A request sent from your project to Slack.
- [Outgoing webhooks](#Outgoing-webhooks)
    A request sent from Slack to your project.


## API methods

The following API methods can be accessed through the ``slack:api`` commands,
or by manipulating the relevant ApiMethod directly.

*TIP: Click on a method to go to the relevant documentation page of the Slack API.*

- search.all
- search.messages
- search.files
- auth.test
- more coming soon!

## Incoming webhooks

Implementation for incoming webhooks has been removed from this bundle because the
API-methods already provide the same functionality that incoming webhooks currently provides
(sending messages to Slack).

## Outgoing webhooks

You can check out the DemoController's methods to see how you can set up a webhook that can be accessed
by Slack and responded to appropriately.

*TIP: Did you know you can even set-up a custom command (called a 'trigger word') within Slack that sends a
request to your controller? For more information about configuring outgoing webhooks, check out [Slack's documentation on this](#).*
