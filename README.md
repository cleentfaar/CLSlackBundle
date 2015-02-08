# SlackBundle [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/cleentfaar/CLSlackBundle/tree/master/LICENSE.md)

Symfony bundle that integrates the [Slack API client](https://github.com/cleentfaar/slack) by providing easy-to-use services and configuration.

If you would like to access the Slack API from the command-line, consider installing the [slack-cli](https://github.com/cleentfaar/slack-cli) package.

[![Build Status](https://img.shields.io/travis/cleentfaar/CLSlackBundle/master.svg?style=flat-square)](https://travis-ci.org/cleentfaar/CLSlackBundle)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/cleentfaar/CLSlackBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/cleentfaar/CLSlackBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle)
[![Latest Version](https://img.shields.io/github/release/cleentfaar/CLSlackBundle.svg?style=flat-square)](https://github.com/cleentfaar/CLSlackBundle/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/cleentfaar/slack-bundle.svg?style=flat-square)](https://packagist.org/packages/cleentfaar/slack-bundle)


## Quick example

Here is an example of how you can access the API's `chat.postMessage` method to send a message to one of your Slack channels:

```php
<?php
// Acme\DemoBundle\Controller\MySlackController

public function sendAction()
{
    $factory  = $this->get('cl_slack.payload_factory');
    $payload  = $factory->chatPostMessage('#general', 'Hello world!', 'acme');
    $response = $this->get('cl_slack.api_client')->send($payload);

    // display the Slack channel ID on which the message was posted
    echo $response->getChannel(); // would return something like 'C01234567'

    // display the Slack timestamp on which the message was posted (note: NON-unix timestamp!)
    echo $response->getTimestamp(); // would return something like '1407190762.000000'
}

```

In Slack, that should give you something like this in the `#general` channel:
![Example of a message posted to Slack](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/img/api-method-chat-postMessage.png)

These and more examples can be found in the [usage](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/usage.md) documentation.


## Documentation

- [Installation](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/installation.md)
- [Usage](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/usage.md)
- [Contributing](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/contributing.md)

Detailed documentation on how to access each API method can be found in the documentation of the package that this bundle integrates: [Slack API library](https://github.com/cleentfaar/slack)


## Thanks

- [@fieg](http://github.com/fieg), for initial ideas about integrating Slack with our projects.
- The guys at [Slack](https://slack.com/), for making an awesome product and clean documentation.


## Contributing

If you would like to contribute to this package, check out the contribution doc [here](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/contributing.md).
