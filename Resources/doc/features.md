# Features

Slack provides three ways of integrating your project with it's platform:

- [API methods](#API-methods) provide the most flexible interaction with the [Slack API](#).
- [Incoming webhooks](#Incoming-webhooks) allow you to send triggers from your project to Slack.
- [Outgoing webhooks](#Outgoing-webhooks) allow you to handle triggers sent from Slack to your project.


## API methods

The API is the most flexible way of interacting with Slack from within your project.
You could do anything like search for specific messages and files, send a message to the channel, and more.

Currently, the following API methods are available. They can be accessed through the ``slack:api`` commands,
or by using their ApiMethod-classes directly (i.e. ``SearchAllApiMethod`` for search.all operations).

*TIP: Click on a method to go to the relevant documentation page of the Slack API.*

- [search.all](https://api.slack.com/methods/search.all)
- [search.messages](https://api.slack.com/methods/search.messages)
- [search.files](https://api.slack.com/methods/search.files)
- [auth.test](https://api.slack.com/methods/auth.test)
- more coming soon!

Further documentation about using the API commands can be found in the [API commands documentation](api-commands.md).
Additionally, you can see how to work with these methods directly in the [API methods documentation](api-methods.md).


## Incoming webhooks

Implementation for incoming webhooks has been removed from this bundle because the
API-methods already provide the same functionality that incoming webhooks currently provides
(sending messages to Slack).


## Outgoing webhooks

### What are they?

Imagine you could write a custom message in Slack that lets you send a request to one of your Symfony projects,
and have it reply with a sensible message in the same channel? Well, you can!

### Get me going!

First, start setting up your own Outgoing Webhook within your Slack Adminstration,
commonly found under https://yourteam.slack.com/services/new (replace 'yourteam' with your team's name).

Then, check out the [usage documentation](outgoing-webhooks.md) on creating webhook responses.
to see how you can set up your own project's webhooks so they can be accessed by Slack and responded
to appropriately. It shows you a very simplified example of a chatbot that you could interact with through a
Slack channel (using the trigger word ``ask``)!


# Ready?

Check out the next chapter about installing the bundle in your Symfony project: [Installation](installation.md).
