<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload\ResponseHelper;

use Guzzle\Http\Message\Response;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ResponseHelper
{
    /**
     * @var \Guzzle\Http\EntityBodyInterface
     */
    protected $responseBody;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->responseBody = $response->getBody(false);
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return (bool) $this->responseBody->getCustomData('ok');
    }
}
