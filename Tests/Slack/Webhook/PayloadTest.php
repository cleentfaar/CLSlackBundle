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
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Payload',
            [$channel, $text]
        );
        $this->assertEquals(
            $channel,
            $payloadMock->getChannel(),
            "The channel given as constructor does not match the value returned by getChannel"
        );
        $this->assertEquals(
            $text,
            $payloadMock->getText(),
            "The text given as constructor does not match the value returned by getText"
        );
    }

    /**
     * @dataProvider getValidChannels
     */
    public function testValidChannel($channel)
    {
        new Payload($channel, 'My test message');

        $this->assertTrue(true, "No exception should be thrown with a valid channel");
    }

    /**
     * @dataProvider getInvalidChannels
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidChannel($channel)
    {
        new Payload($channel, 'My test message');
    }

    /**
     * @dataProvider getValidTexts
     */
    public function testValidText($text)
    {
        new Payload('#foobar', $text);

        $this->assertTrue(true, "No exception should be thrown with a valid text");
    }

    /**
     * @dataProvider getInvalidTexts
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidText($text)
    {
        new Payload('#foobar', $text);
    }

    public function testGetUsername()
    {
        $username = 'testbot';
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Payload',
            ['#foobar', 'My testing message']
        );
        $payloadMock->setUsername($username);

        $this->assertEquals($username, $payloadMock->getUsername());
    }

    public function testGetIcon()
    {
        $icon = ':test:';
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Payload',
            ['#foobar', 'My testing message']
        );
        $payloadMock->setIcon($icon);

        $this->assertEquals($icon, $payloadMock->getIcon());
    }

    /**
     * @dataProvider getPayloads
     */
    public function testToArray(array $payloadArrayBefore, array $payloadArrayAfter)
    {
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Webhook\Payload',
            [$payloadArrayBefore['channel'], $payloadArrayBefore['text']]
        );
        foreach (['username', 'icon'] as $optionalSetter) {
            if (array_key_exists('username', $payloadArrayBefore)) {
                $payloadMock->{'set' . ucfirst($optionalSetter)}($payloadArrayBefore[$optionalSetter]);
            }
        }
        $payloadArrayActual = $payloadMock->toArray();
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
    public function getPayloads()
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

    public function getValidChannels()
    {
        return [
            ['#foobar'],
            ['#apple'],
            ['#pear'],
        ];
    }

    public function getInvalidChannels()
    {
        return [
            ['foobar'],
            [''],
            [null],
        ];
    }

    public function getValidTexts()
    {
        return [
            ['this is a non-empty string'],
            ['here is another one'],
            ['and yet another one'],
        ];
    }

    public function getInvalidTexts()
    {
        return [
            [''],
            [[]],
            [null],
        ];
    }
}
