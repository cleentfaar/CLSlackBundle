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

use CL\Bundle\SlackBundle\Slack\Webhook\Payload;
use CL\Bundle\SlackBundle\Tests\TestCase;

class PayloadTest extends TestCase
{
    public function testConstruct()
    {
        $channel       = '#foobar';
        $text          = 'My test message';
        $transportMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Payload',
            [$channel, $text],
            ['setChannel', 'setText']
        );
        $this->assertEquals($channel, $transportMock->getChannel(), "The channel given as constructor does not match the value returned by getChannel");
        $this->assertEquals($text, $transportMock->getText(), "The text given as constructor does not match the value returned by getText");
    }

    /**
     * @dataProvider getPayloads
     */
    public function testToArray(array $payloadArrayBefore, array $payloadArrayAfter)
    {
        $payload = new Payload($payloadArrayBefore['channel'], $payloadArrayBefore['text']);
        foreach (['username', 'icon'] as $optionalSetter) {
            if (array_key_exists('username', $payloadArrayBefore)) {
                $payload->{'set' . ucfirst($optionalSetter)}($payloadArrayBefore[$optionalSetter]);
            }
        }
        $payloadArrayActual = $payload->toArray();
        foreach (['channel', 'text'] as $requiredKey) {
            $this->assertArrayHasKey(
                $requiredKey,
                $payloadArrayActual,
                sprintf('Every payload array should contain a key named \'%s\'', $requiredKey)
            );
        }

        $this->assertEquals($payloadArrayAfter, $payloadArrayActual, 'Expected payload does not match actual payload');
    }

    /**
     * @return array
     */
    public static function getPayloads()
    {
        return [
            [
                [
                    'channel'  => '#foobar',
                    'text'     => 'Here is my message',
                    'icon'     => ':thisismyicon:',
                    'username' => 'testbot',
                ],
                [
                    'channel'    => '#foobar',
                    'text'       => 'Here is my message',
                    'icon_emoji' => ':thisismyicon:',
                    'username'   => 'testbot',
                ],
            ],
            [
                [
                    'channel' => '#foobar',
                    'text'    => 'Here is my message',
                ],
                [
                    'channel' => '#foobar',
                    'text'    => 'Here is my message',
                ],
            ],
        ];
    }
}
