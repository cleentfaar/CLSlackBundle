<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
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
     * @param string $username
     * @param string $token
     */
    public function __construct($username, $token)
    {
        $this->httpClient = new Client();
        $this->url        = sprintf(
            'https://%s.slack.com/services/hooks/incoming-webhook?token=%s',
            $username,
            $token
        );
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
     * @return EntityEnclosingRequestInterface
     */
    protected function createRequest(Payload $payload)
    {
        /** @var EntityEnclosingRequestInterface $request */
        $request = $this->httpClient->post(
            $this->getUrl(),
            [
                'content-type' => 'application/json',
            ]
        );
        $request->setBody(json_encode($payload->toArray()));

        return $request;
    }

    /**
     * @param Request $request
     *
     * @return array|Response|null
     *
     * @throws \LogicException
     */
    protected function sendRequest(Request $request)
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (\Exception $e) {
            if ($e instanceof ServerErrorResponseException && null !== $e->getResponse()) {
                throw new \LogicException(sprintf(
                    "%s \nthe response body was: \n%s",
                    $e->getMessage(),
                    $e->getResponse()->getBody(true)
                ));
            }

            throw new \LogicException(sprintf("Failed to send request (%d): %s", $e->getCode(), $e->getMessage()));
        }

        if (false === is_object($response) || false === $response instanceof Response) {
            throw new \LogicException("Expected client to return a response, got %s", var_export($response, true));
        }

        return $response;
    }
}
