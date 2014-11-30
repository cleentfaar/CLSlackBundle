# SlackBundle [![License](https://poser.pugx.org/cleentfaar/slack-bundle/license.svg)](https://packagist.org/packages/cleentfaar/slack-bundle)

This bundle provides integration with the Slack library, allowing you to interact with the Slack API within your Symfony projects.

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

public function sendAction()
{
    $payload = $this->get('cl_slack.payload_registry')->get('chat.postMessage');
    $payload->setChannel('#general');
    $payload->setMessage('This message was sent using the <https://github.com/cleentfaar/CLSlackBundle|SlackBundle>!');
    $payload->setIconEmoji(':birthday:');

    $response = $this->get('cl_slack.api_client')->send($payload);

    // display the Slack channel ID on which the message was posted
    // echo $response->getChannel(); // would return something like 'C01234567'

    // display the Slack timestamp on which the message was posted (note: NON-unix timestamp!)
    // echo $response->getTimestamp(); // would return something like '1407190762.000000'
}
```

In Slack, that should give you something like this in the ``#test`` channel:
![Example of a message posted to Slack](img/api-method-chat-postMessage.png)

These and more examples can be found in the [usage](Resources/doc/usage.md) documentation.


## Documentation

Check out the [documentation](Resources/doc/index.md):

- [Installation](Resources/doc/installation.md)
- [Usage](Resources/doc/usage.md)
- [Contributing](Resources/doc/contributing.md)

To get a better understanding of the functionality that this bundle integrates, you should also check out the documentation
of the actual library [here](https://github.com/cleentfaar/slack/Resources/doc/usage.md).


## Thanks

- [@fieg](http://github.com/fieg), for initial ideas about integrating Slack with our projects.
- The guys at [Slack](https://slack.com/), for making an awesome product and clean documentation.


## Contributing

If you would like to contribute to this package, check out the contribution doc [here](Resources/doc/contributing.md).
