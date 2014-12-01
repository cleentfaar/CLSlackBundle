# Usage

The [SlackBundle](https://github.com/cleentfaar/CLSlackBundle) implements the [Slack](https://github.com/cleentfaar/slack) PHP library into your Symfony2 project.
Therefore you can find most of the documentation about interacting with the Slack API [there](https://github.com/cleentfaar/slack/Resources/doc/usage.md).

**NOTE:** The example shown below is specific to this bundle; you should refer to the [library usage docs](https://github.com/cleentfaar/slack/Resources/doc/usage.md)
for examples on setting up the API client manually.


## chat.postMessage: Sending a message to one of your Slack channels

Sending a message to one of your Slack channels is pretty easy. Here is how you could do this inside one of your controllers:
```php
// Acme\DemoBundle\Controller\MySlackController

public function sendAction()
{
    $payload = new ChatPostMessagePayload();
    $payload->setChannel('#general');
    $payload->setMessage('This message was sent using the <https://github.com/cleentfaar/CLSlackBundle|SlackBundle>!');
    $payload->setUsername('acmebot');
    $payload->setIconEmoji(':birthday:');

    $response = $this->get('cl_slack.api_client')->send($payload);

    // display the Slack channel ID on which the message was posted
    echo $response->getChannel(); // would return something like 'C01234567'

    // display the Slack timestamp on which the message was posted (note: NON-unix timestamp!)
    echo $response->getTimestamp(); // would return something like '1407190762.000000'
}
```

In Slack, that should give you something like this in the `#general` channel:
![Example of a message posted to Slack](https://raw.githubusercontent.com/cleentfaar/CLSlackBundle/master/Resources/doc/img/api-method-chat-postMessage.png)


## Handling the response

When you run the example for the first time you may find that no message is actually sent, or an exception is thrown.
This could be for many reasons, but most often it's because the channel you gave does not exist in your Slack Team.
It could also be that the API token you configured in ``app/config/config.yml`` is wrong, as the configured token is
used if you do not pass it as one of the options yourself.

Because there are so many things that Slack might not accept or know how to deal with, you can use the response from
Slack to find out more.

*As a sidenote, even the response is following the same scheme defined in the official Slack API documentation,
so it should feel familiar if you checked it out beforehand.*

```php
// $response = $helper->send('theMethod.slugHere', [...]);
// ...
if (!$response->isOk()) {
    switch ($response->getError()) {
        case ApiMethodResponseInterface::ERROR_CHANNEL_NOT_FOUND:
            throw new \InvalidArgumentException(sprintf("Wait a tick... That channel does not even exist! Given: %s", $channel));
            break;
        case ApiMethodResponseInterface::ERROR_INVALID_TOKEN:
            throw new \InvalidArgumentException("Wait a tick... We got the wrong token configured!");
            break;
        default:
            throw new \InvalidArgumentException($response->getError());
    }
}
```


## Console Commands

Besides making different factories from the library available as services in your project, special commands have been
created for the console application (`php app/console`) that you may find useful to (for example) quickly send messages
to a Slack channel.

For more information about the commands, check out the [Console Commands documentation](commands.md).


## Got it?

If you haven't done so yet, check out the chapter about working with the [Console Commands](commands.md) or [creating a bot](creating-a-bot.md).
