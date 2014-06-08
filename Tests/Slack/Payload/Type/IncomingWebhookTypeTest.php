<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Tests\Slack\Payload\Type;

use CL\Bundle\SlackBundle\Slack\Payload\Payload;
use CL\Bundle\SlackBundle\Slack\Payload\Type\IncomingWebhookType;
use CL\Bundle\SlackBundle\Tests\AbstractTestCase;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class IncomingWebhookTypeTest extends AbstractTestCase
{
    public function testConstruct()
    {
        $options = [
            'channel' => '#foobar',
            'text'    => 'My test message',
        ];
        $payload = $this->getIncomingWebhookMock($options);
        $this->assertEquals(
            $options['channel'],
            $payload->getOptions()['channel'],
            "The (properly formatted) channel given as constructor does not match the value returned by getChannel"
        );
        $this->assertEquals(
            $options['text'],
            $payload->getOptions()['text'],
            "The text given as constructor does not match the value returned by getText"
        );
    }

    /**
     * @dataProvider getValidChannels
     */
    public function testValidChannel($channel)
    {
        $options = [
            'channel' => $channel,
            'text'    => 'My test message',
        ];
        $this->getIncomingWebhookMock($options);

        $this->assertTrue(true, "No exception should be thrown with a valid channel");
    }

    /**
     * @dataProvider getInvalidChannels
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidChannel($channel)
    {
        $options = [
            'channel' => $channel,
            'text'    => 'My test message',
        ];
        $this->getIncomingWebhookMock($options);
    }

    /**
     * @dataProvider getValidTexts
     */
    public function testValidText($text)
    {
        $options = [
            'channel' => '#foobar',
            'text'    => $text,
        ];
        $this->getIncomingWebhookMock($options);

        $this->assertTrue(true, "No exception should be thrown with a valid text");
    }

    /**
     * @dataProvider getInvalidTexts
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidText($text)
    {
        $options = [
            'channel' => '#foobar',
            'text'    => $text,
        ];
        $this->getIncomingWebhookMock($options);
    }

    public function testGetUsername()
    {
        $expected = 'testbot';
        $options  = [
            'channel'  => '#foobar',
            'text'     => 'This is an example text',
            'username' => $expected,
        ];
        $payload  = $this->getIncomingWebhookMock($options);
        $actual   = $payload->getOptions()['username'];

        $this->assertEquals($expected, $actual);
    }

    public function testGetIcon()
    {
        $expected = ':ghost:';
        $options  = [
            'channel'    => '#foobar',
            'text'       => 'This is an example text',
            'icon_emoji' => $expected,
        ];
        $payload  = $this->getIncomingWebhookMock($options);
        $actual   = $payload->getOptions()['icon_emoji'];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider getPayloads
     */
    public function testToArray(array $payloadArrayBefore, array $payloadArrayAfter)
    {
        $payload = $this->getIncomingWebhookMock($payloadArrayBefore);
        $actual  = $payload->getOptions();
        $this->assertEquals($payloadArrayAfter, $actual, 'Expected payload does not match actual payload');
    }

    /**
     * @return array
     */
    public function getPayloads()
    {
        return [
            [
                [
                    'channel'    => '#foobar',
                    'text'       => 'Here is my message',
                    'icon_emoji' => ':thisismyicon:',
                    'username'   => 'testbot',
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
                    'icon_emoji' => '::' // @todo FIX THIS ASAP!
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
            [[]],
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

    /**
     * @param array $options
     *
     * @return Payload
     */
    protected function getIncomingWebhookMock(array $options)
    {
        $incomingWebhook = new IncomingWebhookType();
        $payload         = new Payload($incomingWebhook);
        $payload->setOptions($options);

        return $payload;
    }
}
