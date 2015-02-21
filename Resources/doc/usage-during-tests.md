# Usage during tests

To make your functional tests easier, you can use the `MockApiClient` class to still be able
to access (mocked) responses.


## Using the `cl_slack.mock_api_client` service

For this bundle, a special service has been made to make this a bit easier: `cl_slack.mock_api_client`.
It behaves the same as the normal `cl_slack.api_client` service, the difference being that no
connection is made to the API client and the data returned is mocked.

```php
// Acme\DemoBundle\Tests\AcmeChatService

public function testSend()
{
    $payload  = new ChatPostMessagePayload();
    $payload->setChannel('#general');
    $payload->setText('Hello world!');
    $payload->setUsername('acme');
    $payload->setIconEmoji('birthday');

    // no connection will be made by using the mocked client,
    // it will simply create the proper response for this payload,
    // in this case an instance of ChatPostMessagePayloadResponse,
    // and fill it with some sensible data.
    $response = $this->get('cl_slack.mock_api_client')->send($payload);

    // display the Slack channel ID on which the message was posted
    echo $response->getChannel(); // would return mocked data, like 'C01234567'

    // display the Slack timestamp on which the message was posted (note: NON-unix timestamp!)
    echo $response->getTimestamp(); // would return mocked data, like '12345678.12345678'
}
```

## Using the `test` option

If you configure the bundle's `test` option to `true`, the `cl_slack.api_client` service will become a mocked
API client. This can be useful during functional tests, where you don't want to connect to remote services
but still get a real-life response for your tests.

You can read more out the `test` option in the [configuration](https://github.com/cleentfaar/CLSlackBundle/blob/master/Resources/doc/configuration.md) chapter.
