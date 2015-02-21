# Usage during tests

To make your functional tests easier, you can use the `MockApiClient` class to still be able
to access (mocked) responses.

For this bundle, a special service has been made to make this a bit easier:
```php
// Acme\DemoBundle\Tests\AcmeChatService

public function testSend()
{
    $payload  = new ChatPostMessagePayload();
    $payload->setChannel('#general');
    $payload->setText('Hello world!');
    $payload->setUsername('acme');
    $payload->setIconEmoji('birthday');

    // no connection will be made by using the mocked client, it will simply create the proper response
    // for this payload and fill it with some sensible data.
    $response = $this->get('cl_slack.mock_api_client')->send($payload);

    // display the Slack channel ID on which the message was posted
    echo $response->getChannel(); // would return mocked data, like 'C01234567'

    // display the Slack timestamp on which the message was posted (note: NON-unix timestamp!)
    echo $response->getTimestamp(); // would return mocked data, like '12345678.12345678'
}
```

You can read more out the `MockApiClient` class itself in the *library's* documentation [here](https://github.com/cleentfaar/slack/blob/master/src/CL/Slack/Test/Transport/MockApiClient.php).
