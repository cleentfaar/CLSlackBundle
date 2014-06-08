<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload\Transport;

use CL\Bundle\SlackBundle\Slack\Payload\PayloadInterface;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
interface TransportInterface
{
    /**
     * @param string $url
     */
    public function __construct($url);

    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @return Response
     */
    public function getResponse();

    /**
     * @return ClientInterface
     */
    public function getHttpClient();

    /**
     * @param PayloadInterface $payload
     *
     * @return mixed
     */
    public function send(PayloadInterface $payload);
}
