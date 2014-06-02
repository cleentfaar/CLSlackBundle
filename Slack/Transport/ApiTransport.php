<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Transport;

use CL\Bundle\SlackBundle\Slack\Payload\ApiPayload;
use CL\Bundle\SlackBundle\Slack\Payload\PayloadInterface;
use CL\Bundle\SlackBundle\Slack\Payload\Type\AbstractApiType;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiTransport extends AbstractTransport
{
    const BASE_URL = '';

    /**
     * @param PayloadInterface $payload
     *
     * @return \Guzzle\Http\Message\Response
     *
     * @throws \InvalidArgumentException
     */
    public function send(PayloadInterface $payload)
    {
        /** @var AbstractApiType $type */
        $type = $payload->getType();
        if (!($type instanceof AbstractApiType)) {
            throw new \InvalidArgumentException("Can only transport payloads with types inheriting the AbstractApiType class");
        }

        $url = $this->getUrl();
        $url = sprintf($url, $type->getMethodSlug());
        $url .= '&'.http_build_query($payload->getOptions());
        $this->setUrl($url);

        return parent::sendPayload($payload);
    }
}
