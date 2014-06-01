<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Tests\Slack\Webhook;

use CL\Bundle\SlackBundle\Slack\Payload\Type\IncomingWebhookType;
use CL\Bundle\SlackBundle\Slack\Transport\IncomingWebhookTransport;
use CL\Bundle\SlackBundle\Tests\TestCase;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class IncomingWebhookTransportTest extends TestCase
{
    public function testGetUrl()
    {
        $url = 'http://my-testing-url.com';

        /** @var IncomingWebhookTransport|\PHPUnit_Framework_MockObject_MockObject $transportMock */
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Transport\IncomingWebhookTransport',
            [$url],
            ['setUrl']
        );
        $this->assertEquals($url, $transportMock->getUrl(), 'Expected url does not match actual url');
    }

    public function testSend()
    {
        /** @var IncomingWebhookType|\PHPUnit_Framework_MockObject_MockObject $payloadMock */
        $payloadMock = $this->getCustomMock('\CL\Bundle\SlackBundle\Slack\Payload\Type\IncomingWebhookType');

        /** @var IncomingWebhookTransport|\PHPUnit_Framework_MockObject_MockObject $transportMock */
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Transport\WebhookTransport',
            null,
            ['send']
        );
        $responseMock = $this->getCustomMock('\Guzzle\Http\Message\Response');

        $transportMock->expects($this->once())->method('send')->with($payloadMock)->will(
            $this->returnValue($responseMock)
        );

        $this->assertInstanceOf('\Guzzle\Http\Message\Response', $transportMock->send($payloadMock));
    }
}
