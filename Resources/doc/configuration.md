# Configuration

Below is a complete reference of all the configuration options that you can use for this bundle.


### Default configuration
```yaml
cl_slack:
    api_token: ~
    test:      false
```

##### api_token (optional, default: `NULL`):
If you don't have an API token yet, follow this link: [https://api.slack.com/web](https://api.slack.com/web).
It takes you to the Slack API site which (if you are logged in, then scroll down) lets you generate an API token for your account.
You can leave this setting empty for now, but you will then have to provide it when sending payloads, for example:
```php
<?php
// ...
$apiClient->send($payload, 'your-token-here');
// ...
```

##### test (optional, default: `false`):
This option can be set to true so that the `cl_slack.api_client` service will use a mocked version
of the `ApiClient` class (i.e. the `MockApiClient` class).

This can be useful during functional tests, where you don't want to actually connect
with the Slack API, but still get a more or less real-life response for whatever request you are handling.

For more information, check out the [Usage during tests](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/usage-during-tests.md) documentation.
