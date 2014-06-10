CLSlackBundle
=============
The **CLSlackBundle** allows your Symfony project to interact with your team's Slack API and webhooks.
This can be done through either a ``Controller`` (responding to webhooks) or specific console commands (for direct API access).

[![Build Status](https://secure.travis-ci.org/cleentfaar/CLSlackBundle.png)](http://travis-ci.org/cleentfaar/CLSlackBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/cleentfaar/CLSlackBundle/?branch=master)


### What now?

Check out the documentation below...

* [Features](Resources/doc/features.md)
* [Installation](Resources/doc/installation.md)
* [Usage](Resources/doc/features.md)
* Additionally, check out the [API documentation](https://api.slack.com/) of Slack itself.

...and start posting some messages!

``slack:api:send-message MyChannel "This bundle rocks!" --username=MyName``


## Contributors

[@fieg](http://github.com/fieg), for initial ideas about integrating Slack with our projects.
The guys at [Slack](https://slack.com/), for making an awesome product and clean documentation.
