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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class IncomingWebhookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'channel',
            'text',
        ]);
        $resolver->setOptional([
            'username',
            'icon_emoji',
        ]);
        $resolver->setAllowedTypes([
            'channel'    => ['string'],
            'text'       => ['string'],
            'username'   => ['string'],
            'icon_emoji' => ['string'],
        ]);
        $resolver->setNormalizers([
            'icon_emoji' => function (Options $options, $value) {
                return ':'.trim($value, ':').':';
            },
            'channel' => function (Options $options, $value) {
                if (empty($value)) {
                    throw new \InvalidArgumentException("You must supply a non-empty string as the channel");
                }

                return '#'.ltrim($value, '#');
            },
            'text' => function (Options $options, $value) {
                if (empty($value)) {
                    throw new \InvalidArgumentException("You must supply a non-empty string for the text");
                }

                return $value;
            },
        ]);
    }
}
