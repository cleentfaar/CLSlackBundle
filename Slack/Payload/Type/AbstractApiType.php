<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload\Type;

use CL\Bundle\SlackBundle\Slack\Payload\PayloadInterface;
use CL\Bundle\SlackBundle\Slack\Payload\ResponseHelper\ResponseHelper;
use CL\Bundle\SlackBundle\Slack\Payload\Transport\TransportInterface;
use Guzzle\Http\Message\Response;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiType extends AbstractType
{
    /**
     * Returns the API method slug for this type.
     *
     * @return string The method slug for this type
     */
    abstract public function getMethodSlug();

    /**
     * {@inheritdoc}
     */
    public function createRequest(PayloadInterface $payload, TransportInterface $transport)
    {
        $client  = $transport->getHttpClient();
        $request = $client->createRequest('get', sprintf($client->getBaseUrl(), $this->getMethodSlug()));
        $request->getQuery()->merge($payload->getOptions());

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function createResponseHelper(Response $response)
    {
        return new ResponseHelper($response);
    }
}
