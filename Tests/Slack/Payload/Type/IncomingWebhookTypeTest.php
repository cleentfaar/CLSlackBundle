<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Tests\Slack\Payload\Type;

use CL\Bundle\SlackBundle\Slack\Payload\Payload;
use CL\Bundle\SlackBundle\Slack\Payload\Type\IncomingWebhookType;
use CL\Bundle\SlackBundle\Tests\TestCase;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class IncomingWebhookTypeTest extends TestCase
{
    public function testConstruct()
    {
        $channel       = '#foobar';
        $text          = 'My test message';
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Payload\Type\IncomingWebhookType',
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
        new IncomingWebhookType($channel, 'My test message');

        $this->assertTrue(true, "No exception should be thrown with a valid channel");
    }

    /**
     * @dataProvider getInvalidChannels
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidChannel($channel)
    {
        new IncomingWebhookType($channel, 'My test message');
    }

    /**
     * @dataProvider getValidTexts
     */
    public function testValidText($text)
    {
        new IncomingWebhookType('#foobar', $text);

        $this->assertTrue(true, "No exception should be thrown with a valid text");
    }

    /**
     * @dataProvider getInvalidTexts
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidText($text)
    {
        new IncomingWebhookType('#foobar', $text);
    }

    public function testGetUsername()
    {
        $username = 'testbot';

        /** @var Payload|\PHPUnit_Framework_MockObject_MockObject $payloadMock */
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Payload\Payload',
            ['#foobar', 'My testing message']
        );
        $payloadMock->setUsername($username);

        $this->assertEquals($username, $payloadMock->getUsername());
    }

    public function testGetIcon()
    {
        $icon = ':test:';
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Payload\Payload',
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
        /** @var Payload|\PHPUnit_Framework_MockObject_MockObject $payloadMock */
        $payloadMock = $this->getCustomMock(
            '\CL\Bundle\SlackBundle\Slack\Payload\Payload',
            [$payloadArrayBefore['channel'], $payloadArrayBefore['text']]
        );
        foreach (['username', 'icon'] as $optionalSetter) {
            if (array_key_exists('username', $payloadArrayBefore)) {
                $payloadMock->{'set' . ucfirst($optionalSetter)}($payloadArrayBefore[$optionalSetter]);
            }
        }
        $payloadArrayActual = $payloadMock->getOptions();
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
