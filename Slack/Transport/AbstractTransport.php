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
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractTransport implements TransportInterface
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $url;

    /**
     * {@inheritdoc}
     */
    public function __construct($url)
    {
        $this->httpClient = new Client();
        $this->url        = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    protected function sendPayload(PayloadInterface $payload)
    {
        $request = $this->createRequest($payload);
        $response = $this->sendRequest($request);

        return $response;
    }

    /**
     * @param string $url
     */
    protected function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param PayloadInterface $payload
     *
     * @return Request
     */
    protected function createRequest(PayloadInterface $payload)
    {
        $request = $this->httpClient->post(
            $this->getUrl(),
            [
                'content-type' => 'application/json',
            ],
            json_encode($payload->getOptions())
        );

        return $request;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \LogicException
     */
    protected function sendRequest(Request $request)
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (BadResponseException $e) {
            throw new \LogicException(sprintf(
                "Failed to send request (%d): %s, \nthe response body was: \n%s",
                $e->getResponse()->getStatusCode(),
                $e->getMessage(),
                $e->getResponse()->getBody(true)
            ));
        }

        if (false === is_object($response) || false === $response instanceof Response) {
            throw new \LogicException(sprintf(
                "Expected client to return a Response instance, got %s",
                var_export($response, true)
            ));
        }

        return $response;
    }
}
