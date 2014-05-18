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
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class TransportTest extends TestCase
{
    /**
     * @dataProvider getUrls
     */
    public function testConstruct($url)
    {
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Transport',
            [$url],
            ['setUrl']
        );
        $this->assertEquals($url, $transportMock->getUrl(), 'Expected url does not match actual url');
    }

    /**
     * @dataProvider getUrls
     */
    public function testSend($url)
    {
        $payloadMock   = $this->getCustomMock('\CL\Bundle\SlackBundle\Slack\Webhook\Payload');
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Transport',
            null,
            [
                'createRequest',
                'sendRequest'
            ]
        );
        $request       = new Request('GET', $url);
        $response      = new Response(200);

        $transportMock->expects($this->once())->method('createRequest')->will($this->returnValue($request));
        $transportMock->expects($this->once())->method('sendRequest')->with($request)->will(
            $this->returnValue($response)
        );

        $transportMock->send($payloadMock);
    }

    /**
     * @return array
     */
    public static function getUrls()
    {
        return [
            ['http://my-testing-url.com'],
        ];
    }
}
