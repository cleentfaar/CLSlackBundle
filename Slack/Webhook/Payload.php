<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Webhook;

class Payload
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $channel;

    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $icon;

    /**
     * @param string $channel
     * @param string $text
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($channel, $text)
    {
        if (!is_string($channel) || empty($channel) || '#' !== substr($channel, 0, 1)) {
            throw new \InvalidArgumentException(sprintf(
                "A channel must be a non-empty string, and start with a hash (#), got: %s",
                var_export($channel, true)
            ));
        }
        $this->channel = $channel;

        if (!is_string($text) || empty($text)) {
            throw new \InvalidArgumentException(sprintf(
                "The text to send must be a non-empty string, got: %s",
                var_export($text, true)
            ));
        }
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param null|string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param null|string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return null|string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $payload = [];
        $payload['channel'] = $this->getChannel();
        $payload['text'] = $this->getText();

        if (null !== $username = $this->getUsername()) {
            $payload['username'] = $username;
        }

        if (null !== $icon = $this->getIcon()) {
            $payload['icon_emoji'] = $icon;
        }

        return $payload;
    }
}
