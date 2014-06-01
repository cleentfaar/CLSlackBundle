<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class CLSlackExtension extends Extension
{
    /**
     * @param array[]          $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->setParameters($container, $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param string[]         $config
     */
    protected function setParameters(ContainerBuilder $container, array $config)
    {
        if ($config['api_token'] === null) {
            $container->removeDefinition('cl_slack.api.transport');
        } else {
            // note the replaceable variable (%s); the ApiTransport will replace it with the proper slug
            $container->setParameter('cl_slack.api_base_url', 'https://slack.com/api/%s?token='.$config['api_token']);
        }

        if ($config['incoming_webhook_token'] === null || $config['team'] === null) {
            $container->removeDefinition('cl_slack.incoming_webhook.transport');
        } else {
            $payloadUrl = sprintf(
                'https://%s.slack.com/services/hooks/incoming-webhook?token=%s',
                $config['team'],
                $config['incoming_webhook_token']
            );
            $container->setParameter('cl_slack.incoming_webhook_url', $payloadUrl);
        }
    }
}
