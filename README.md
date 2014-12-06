# SlackBundle [![License](https://poser.pugx.org/cleentfaar/slack-bundle/license.svg)](https://packagist.org/packages/cleentfaar/slack-bundle)

Symfony bundle that let's you access the Slack API by integrating the [Slack API client](https://github.com/cleentfaar/slack) package.

Besides providing easy-to-access services, **commands are provided for all of the API methods** so you
 can easily set-up cronjobs to handle Slack automation, or just be geeky :smile:...

[![Build Status](https://secure.travis-ci.org/cleentfaar/CLSlackBundle.svg)](http://travis-ci.org/cleentfaar/CLSlackBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/?branch=master)<br/>
[![Latest Stable Version](https://poser.pugx.org/cleentfaar/slack-bundle/v/stable.svg)](https://packagist.org/packages/cleentfaar/slack-bundle)
[![Total Downloads](https://poser.pugx.org/cleentfaar/slack-bundle/downloads.svg)](https://packagist.org/packages/cleentfaar/slack-bundle)
[![Latest Unstable Version](https://poser.pugx.org/cleentfaar/slack-bundle/v/unstable.svg)](https://packagist.org/packages/cleentfaar/slack-bundle)


## Quick example

Here is an example of how you can access the API's `chat.postMessage` method to send a message to one of your Slack channels:

```php
// Acme\DemoBundle\Controller\MySlackController

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
```

In Slack, that should give you something like this in the `#general` channel:
![Example of a message posted to Slack](https://raw.githubusercontent.com/cleentfaar/CLSlackBundle/master/Resources/doc/img/api-method-chat-postMessage.png)

These and more examples can be found in the [usage](Resources/doc/usage.md) documentation.


## Documentation

- [Installation](Resources/doc/installation.md)
- [Usage](Resources/doc/usage.md)
- [Commands](Resources/doc/commands.md)
- [Contributing](Resources/doc/contributing.md)

Detailed documentation on how to access each API method can be found in the documentation of the package that this bundle integrates: [Slack API client](https://github.com/cleentfaar/slack)

To get a better understanding of the functionality that this bundle integrates, you should also check out the documentation
of the actual library [here](https://github.com/cleentfaar/slack/Resources/doc/usage.md).


## Thanks

- [@fieg](http://github.com/fieg), for initial ideas about integrating Slack with our projects.
- The guys at [Slack](https://slack.com/), for making an awesome product and clean documentation.


## Contributing

If you would like to contribute to this package, check out the contribution doc [here](Resources/doc/contributing.md).
