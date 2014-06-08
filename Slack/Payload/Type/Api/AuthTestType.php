<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Slack\Payload\Type\Api;

use CL\Bundle\SlackBundle\Slack\Payload\PayloadInterface;
use CL\Bundle\SlackBundle\Slack\Payload\Transport\TransportInterface;
use CL\Bundle\SlackBundle\Slack\Payload\Type\AbstractApiType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * This type allows you to test authenticating with the Slack API.
 *
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class AuthTestType extends AbstractApiType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['token']);
        $resolver->setAllowedTypes([
            'token' => ['string'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(PayloadInterface $payload, TransportInterface $transport)
    {
        $request = parent::createRequest($payload, $transport);

        // we need to actually replace existing query parameters here because
        // with this command we can expect a token to be passed through manually,
        // and the base url already has a token set by default
        $request->getQuery()->replace($payload->getOptions());

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodSlug()
    {
        return 'auth.test';
    }
}
