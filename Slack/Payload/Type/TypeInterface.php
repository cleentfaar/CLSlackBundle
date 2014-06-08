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
use CL\Bundle\SlackBundle\Slack\Payload\Response\ResponseHelperInterface;
use CL\Bundle\SlackBundle\Slack\Payload\Transport\TransportInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
interface TypeInterface
{
    /**
     * @param OptionsResolverInterface $resolver The resolver for the options
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver);

    /**
     * @param PayloadInterface   $payload
     * @param TransportInterface $transport
     *
     * @return Request
     */
    public function createRequest(PayloadInterface $payload, TransportInterface $transport);

    /**
     * @param Response $response
     *
     * @return ResponseHelperInterface
     */
    public function createResponseHelper(Response $response);
}
