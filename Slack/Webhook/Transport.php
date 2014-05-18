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

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class Transport
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
     * @param string $url
     */
    public function __construct($url)
    {
        $this->httpClient = new Client();
        $this->url        = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Payload $payload
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function send(Payload $payload)
    {
        $request = $this->createRequest($payload);
        $response = $this->sendRequest($request);

        return $response;
    }

    /**
     * @param Payload $payload
     *
     * @return Request
     */
    protected function createRequest(Payload $payload)
    {
        $request = $this->httpClient->post(
            $this->getUrl(),
            [
                'content-type' => 'application/json',
            ],
            json_encode($payload->toArray())
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
