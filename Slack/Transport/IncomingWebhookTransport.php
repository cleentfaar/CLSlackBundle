<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Transport;

use CL\Bundle\SlackBundle\Slack\Payload\PayloadInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class IncomingWebhookTransport extends AbstractTransport
{
    /**
     * @param PayloadInterface $payload
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function send(PayloadInterface $payload)
    {
        return parent::sendPayload($payload);
    }
}
