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

use CL\Bundle\SlackBundle\Tests\TestCase;

class TransportTest extends TestCase
{
    public function testGetUrl()
    {
        $url = 'http://my-testing-url.com';
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Transport',
            [$url],
            ['setUrl']
        );
        $this->assertEquals($url, $transportMock->getUrl(), 'Expected url does not match actual url');
    }

    public function testCreateRequest()
    {
        $payloadMock = $this->getCustomMock('\CL\Bundle\SlackBundle\Slack\Webhook\Payload');
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Transport',
            null,
            ['createRequest', 'sendRequest']
        );
        $requestMock = $this->getCustomMock('\Guzzle\Http\Message\Request');

        $transportMock->expects($this->once())->method('createRequest')->with($payloadMock)->will(
            $this->returnValue($requestMock)
        );

        $transportMock->send($payloadMock);
    }

    public function testSendRequest()
    {
        $payloadMock = $this->getCustomMock('\CL\Bundle\SlackBundle\Slack\Webhook\Payload');
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Transport',
            null,
            ['createRequest', 'sendRequest']
        );
        $requestMock = $this->getCustomMock('\Guzzle\Http\Message\Request');
        $responseMock = $this->getCustomMock('\Guzzle\Http\Message\Response');

        $transportMock->expects($this->once())->method('createRequest')->with($payloadMock)->will(
            $this->returnValue($requestMock)
        );

        $transportMock->expects($this->once())->method('sendRequest')->with($requestMock)->will(
            $this->returnValue($responseMock)
        );

        $transportMock->send($payloadMock);
    }

    public function testSend()
    {
        $payloadMock = $this->getCustomMock('\CL\Bundle\SlackBundle\Slack\Webhook\Payload');
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Transport',
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
