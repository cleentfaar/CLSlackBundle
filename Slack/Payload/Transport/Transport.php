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
use CL\Bundle\SlackBundle\Slack\Payload\Response\ResponseHelperInterface;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class Transport implements TransportInterface
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $httpClient;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ResponseHelperInterface
     */
    protected $responseHelper;

    /**
     * {@inheritdoc}
     */
    public function __construct($baseUrl)
    {
        $this->httpClient = new Client($baseUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseHelper()
    {
        return $this->responseHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function send(PayloadInterface $payload)
    {
        $this->response       = null;
        $this->request        = $payload->getType()->createRequest($payload, $this);
        $this->response       = $this->sendRequest($this->request);
        $this->responseHelper = $payload->getType()->createResponseHelper($this->response);

        return $this->response;
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
            $response = $this->getHttpClient()->send($request);
        } catch (BadResponseException $e) {
            throw new \LogicException(sprintf(
                "Failed to send request: \n%s, \n[body-sent] %s\n[body-returned] %s",
                $e->getMessage(),
                implode(",", $request->getParams()->toArray()),
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
